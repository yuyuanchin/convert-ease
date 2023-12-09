<?php
function uploadPDFFiles($files){

    if($files['pdfFiles']['name'][0] == "")

        return "Please select at least one file";

    $uploadDir = "uploads/";
    $downloadDir = "downloads/";

    $conversionType = $_POST["conversionType"];

    $names = $files['pdfFiles']['name'];
    $tmp_names = $files['pdfFiles']['tmp_name'];

    $files_array = array_combine($tmp_names, $names);

    // save file in uploads folder
    foreach($files_array as $tmp_folder => $image_name){
        move_uploaded_file($tmp_folder, $uploadDir.$image_name);
    }

    $outputFile = $downloadDir . ($conversionType == "txt2pdf" ? "pdf" : "txt");

    foreach($files_array as $tmp_folder => $image_name){
        $inputFile= $uploadDir.$image_name;
        $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar PDFConverter $inputFile $outputFile $conversionType";

        exec($javaCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            // Log or handle the error
            return "Conversion failed: " . implode("\n", $output);
        }
    }

    return "success";

}

function uploadPDFFiles2($files){
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdfFiles'])) {
        $uploadDir = "uploads/"; // Directory to store uploaded files
        $downloadDir = "downloads/";

        // Assuming the first file in the array
        $pdfFile = $uploadDir . basename($_FILES["pdfFiles"]["name"][0]);
        move_uploaded_file($_FILES['pdfFiles']['tmp_name'][0], $pdfFile);

        $conversionType = $_POST['conversionType'];
        $outputFile = $downloadDir . pathinfo($pdfFile, PATHINFO_FILENAME) . "." . ($conversionType == "txt2pdf" ? "pdf" : "txt");

        // Make sure to define $inputFile
        $inputFile = $pdfFile;

        // Call Java application
        // Call Java application
        $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar NewPDFConverter \"$inputFile\" \"$outputFile\" $conversionType";


        exec($javaCommand, $output, $returnVar);

        // Print the output for debugging
        echo "Java Output:<br>";
        echo nl2br(implode("\n", $output));

        if ($returnVar === 0) {
            echo "Conversion complete. <a href='$outputFile' download>Download Result</a>";
        } else {
            echo "Conversion failed. Return code: $returnVar";
            return "Conversion failed. Output: " . implode("\n", $output);
        }
    }
    return "success";
}




function downloadFile($filename) {
    $file_path = "downloads/" . $filename;

    if (file_exists($file_path)) {
        // Sanitize the filename
        $sanitized_filename = preg_replace("/[^\w\-_.]/", "", basename($filename));

        // Send appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $sanitized_filename . '"');
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
