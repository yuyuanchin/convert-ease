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

    foreach ($files_array as $tmp_folder => $image_name) {
    $inputFile = $uploadDir . $image_name;

    // Compile the Java source file
    $compileCommand = "javac PDFConverter.java";
    exec($compileCommand, $compileOutput, $compileReturnVar);

    if ($compileReturnVar !== 0) {
        // Handle compilation error
        return "Compilation failed: " . implode("\n", $compileOutput);
    }

    // Execute the compiled Java class
    $executeCommand = "java PDFConverter $inputFile $outputFile $conversionType";
    exec($executeCommand, $executeOutput, $executeReturnVar);

    if ($executeReturnVar !== 0) {
        // Handle execution error
        return "Execution failed: " . implode("\n", $executeOutput);
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
