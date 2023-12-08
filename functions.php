<?php
function uploadPDFFiles($files){

    if($files['pdfFiles']['name'][0] == "")

        return "Please select at least one file";

    $uploadDir = "uploads/";
    $downloadDir = "download/";

    $conversionType = $_POST["conversionType"];

    $names = $files['pdfFiles']['name'];
    $tmp_names = $files['pdfFiles']['tmp_name'];

    $files_array = array_combine($tmp_names, $names);

    // save file in uploads folder
    foreach($files_array as $tmp_folder => $image_name){
        move_uploaded_file($tmp_folder, $uploadDir.$image_name);
    }

    $outputFile = $downloadDir . ($conversionType == "pdf2txt" ? "txt" : "pdf");

    foreach($files_array as $tmp_folder => $image_name){
        $inputFile= $uploadDir.$image_name;
        $javaCommand = "java -jar PDFConverter.jar $inputFile $outputFile $conversionType";
        exec($javaCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            // Log or handle the error
            return "Conversion failed: " . implode("\n", $output);
        }
    }

    return "success";

}

function uploadPDFFiles2($files){

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uploadDir = "uploads/"; // Directory to store uploaded files
        $downloadDir = "download/";
        $pdfFile = $uploadDir . basename($_FILES["pdfFile"]["name"]);
        move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $pdfFile);

        $conversionType = $_POST["conversionType"];
        $outputFile = $downloadDir . ($conversionType == "pdf2txt" ? "txt" : "pdf");

        // Call Java application
        $javaCommand = "java -jar PDFConverter.jar $pdfFile $outputFile $conversionType";
        exec($javaCommand, $output, $returnVar);

        if ($returnVar === 0) {
            echo "Conversion complete. <a href='$outputFile' download>Download Result</a>";
        } else {
            return "Conversion failed.";
        }
    }
    return "success";

}

function downloadFile($filename) {
    $file_path = "download/" . $filename;

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

function deleteFiles() {
    // Delete files in the "uploads" directory
    $uploadDirectory = 'uploads/';
    $files = glob($uploadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }

    $downloadDirectory = 'download/';
    $files = glob($downloadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }
}
?>
