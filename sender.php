<?php
echo php_ini_loaded_file();
const UPLOAD_DIR = 'uploads/';
const DOWNLOAD_DIR = 'downloads/';

error_reporting(E_ALL);
ini_set('display_errors', true);

if (isset($_GET['deleteFiles']) && $_GET['deleteFiles'] === 'true') {
    deleteFiles();
}

if (isset($_GET['download']) && $_GET['download'] === 'true') {
    $conversionType = $_GET['conversionType'];
    $filename = isset($_GET['file']) ? strtolower($_GET['file']) : '';

    echo "Current Working Directory: " . getcwd() . "<br>";

    // Debugging: Output the list of files in the downloads directory
    $filesInDirectory = scandir('downloads/');
    echo "Files in downloads directory: " . implode(', ', $filesInDirectory) . "<br>";


    // Debugging: Output the requested filename
    echo "Requested Filename: $filename<br>";

    // Construct the file path
    $filePath = 'downloads/' . $filename;

    // Debugging: Output the constructed file path
    echo "Constructed File Path: $filePath<br>";

    if (file_exists($filePath)) {
        // Set appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));

        // Clear output buffer to ensure clean download
        ob_clean();
        flush();

        // Read and output the file
        readfile($filePath);
        exit();
    } else {
        // Debugging: Output a message if the file is not found
        echo "File not found: $filename";
    }
} else {
    // Invalid download request
    echo "Invalid download request";
}

if (!empty($_FILES['file'])) {

    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    if (!file_exists(DOWNLOAD_DIR)) {
        mkdir(DOWNLOAD_DIR, 0777, true);
    }

    //$conversionType = $_POST["conversionType"];

    $targetDir = 'uploads/';
    $filename = basename($_FILES['file']['name']);
    $targetFilePath = $targetDir . $filename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
        convertFile("pdf2txt");
        echo 'File Uploaded';
    }

}

function convertFile($conversionType)
{
    $uploadDir = UPLOAD_DIR;
    $downloadDir = DOWNLOAD_DIR;

    // Get all files in the upload directory
    $files = scandir($uploadDir);

    foreach ($files as $file) {
        // Exclude current directory (.) and parent directory (..)
        if ($file != "." && $file != "..") {
            $inputFile = $uploadDir . $file;

            // Determine the output file based on the conversion type
            $outputFile = $downloadDir . pathinfo($file, PATHINFO_FILENAME) . "." . ($conversionType === "txt2pdf" ? "pdf" : "txt");

            // Construct the Java command
            $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar NewPDFConverter \"$inputFile\" \"$outputFile\" $conversionType";

            // Execute the Java command
            exec($javaCommand, $output, $returnVar);

            if ($returnVar !== 0) {
                // Log or handle the error
                echo "Conversion failed for file $file. Please check the error logs for more details.";
            }
        }
    }
}

function downloadFile($filename) {
    $file_path = 'downloads/' . $filename;

    if (file_exists($file_path)) {
        // Send appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file_path));
        // Clear output buffer to ensure clean download
        ob_clean();
        flush();

        // Read and output the file
        readfile($file_path);
        exit();
    } else {
        // File not found
        echo "File not found: $filename";
    }
}

function deleteFiles()
{
    // Delete files in the "uploads" directory
    $uploadDirectory = 'uploads/';
    $files = glob($uploadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                // Log a success message
                echo "File deleted successfully: $file";
            } else {
                // Log an error message
                echo "Error deleting file: $file";
            }
        }
    }

    // Delete files in the "downloads" directory
    $downloadDirectory = 'downloads/';
    $files = glob($downloadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                // Log a success message
                echo "File deleted successfully: $file";
            } else {
                // Log an error message
                echo "Error deleting file: $file";
            }
        }
    }
}

?>