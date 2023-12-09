<?php

echo php_ini_loaded_file();

const UPLOAD_DIR = 'uploads/';
const DOWNLOAD_DIR = 'downloads/';

if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'convertease.com') !== false) {
    // The script was accessed from a page on your domain
    deleteFiles();
}

//if(isset($_POST['download'])){
//    $conversionType = $_POST['conversionType'];
//    $filename = isset($_POST['file']) ? $_POST['file'] : '';
//    downloadFile($filename);
//}

//if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
//    deleteFiles();
//}

if (!empty($_FILES['file'])) {

    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    if (!file_exists(DOWNLOAD_DIR)) {
        mkdir(DOWNLOAD_DIR, 0777, true);
    }

    $conversionType = $_POST["conversionType"];

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
    $file_path = "downloads/" . $filename;

    if (file_exists($file_path)) {
        // Send appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($file_path);
        exit();
    } else {
        // File not found
        echo "File not found.";
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