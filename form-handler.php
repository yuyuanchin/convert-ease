<?php
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

echo exec('whoami');

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    deleteFiles();
}


//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    // The script was accessed from a page on your domain
//    echo "Script accessed from your domain.";
//} else {
//    // The script was accessed directly or from an external domain
//    echo "Script accessed directly or from an external domain.";
//}

//if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'convertease.com') !== false) {
//    // The script was accessed from a page on your domain
//    deleteFiles();
//}

//$response = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload'])) {
        if (isset($_FILES['uploaded-files'])) {
            uploadFiles($_FILES);
        }
    }
}
//if(isset($_FILES['uploaded-files'])){
//    uploadFiles($_FILES);
//}

if(isset($_POST['download'])){
    $filename = isset($_POST['file']) ? $_POST['file'] : '';
    downloadFile($filename);
}

function uploadFiles($files){

    if($files['uploaded-files']['name'][0] == ""){
        echo "Please select at least one file";
        header("Location: index.php");
        exit();
    }

    $uploadDir = "uploads/";
    $downloadDir = "downloads/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!file_exists($downloadDir)) {
        mkdir($downloadDir, 0777, true);
    }

    $conversionType = $_POST["conversionType"];

    $names = $files['uploaded-files']['name'];
    $tmp_names = $files['uploaded-files']['tmp_name'];

    $files_array = array_combine($tmp_names, $names);

    foreach($files_array as $tmp_folder => $image_name){
        move_uploaded_file($tmp_folder, $uploadDir.$image_name);
        $outputFile = $downloadDir . pathinfo($image_name, PATHINFO_FILENAME) . "." . ($conversionType == "txt2pdf" ? "pdf" : "txt");

//        if($conversionType === "pdf2txt"){
//            $outputFile = $downloadDir . pathinfo($image_name, PATHINFO_FILENAME).".txt";
//        } elseif ($conversionType === "txt2pdf"){
//            $outputFile = $downloadDir . pathinfo($image_name, PATHINFO_FILENAME).".pdf";
//        }
        $inputFile= $uploadDir.$image_name;

        $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar NewPDFConverter \"$inputFile\" \"$outputFile\" $conversionType";

        exec($javaCommand,$output, $returnVar);

        if ($returnVar !== 0) {
            // Log or handle the error
            echo "Conversion failed: " . implode("\n", $output);
        }
    }

    if($conversionType === "pdf2txt"){
        header("Location: index.php");
    } elseif ($conversionType === "txt2pdf"){
        header("Location: txt-to-pdf.html");
    }

//        downloadFile();
//        header("Location: index.html");
//        exit();
//        if ($returnVar !== 0) {
//            // Log or handle the error
//            return "Conversion failed. Please try again. " . implode("\n", $output);
//        }

//    return "success";

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
  