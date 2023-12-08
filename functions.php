<?php
function uploadPDFFiles($files){

    if($files['pdfFiles']['name'][0] == "")
        return "Please select at least one file";

    $uploadDir = "uploads/";
    $downloadDir = "downloads/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!file_exists($downloadDir)) {
        mkdir($downloadDir, 0777, true);
    }

    $conversionType = $_POST["conversionType"];

    $names = $files['pdfFiles']['name'];
    $tmp_names = $files['pdfFiles']['tmp_name'];

    $files_array = array_combine($tmp_names, $names);

    // save file in uploads folder
    foreach($files_array as $tmp_folder => $image_name){
        move_uploaded_file($tmp_folder, $uploadDir.$image_name);

        $outputFile = $downloadDir . pathinfo($image_name, PATHINFO_FILENAME).".txt";
        $inputFile= $uploadDir.$image_name;

        $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar PDFConverter $inputFile $outputFile $conversionType";

        exec($javaCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            // Log or handle the error
            return "Conversion failed. Please try again. " . implode("\n", $output);
        }
    }

    return "success";

}

function downloadTXTFile($filename) {
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

function deleteFiles() {
    // Delete files in the "uploads" directory
    $uploadDirectory = 'uploads/';
    $files = glob($uploadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }

    $downloadDirectory = 'downloads/';
    $files = glob($downloadDirectory . '*');
    foreach ($files as $file) {
        if (is_file($file))
            unlink($file);
    }
}
?>
