<?php
function uploadPDFFiles($files){

    if($files['pdffiles']['name'][0] == "")

        return "Please select at least one file";

    $folder = "uploads/";

    $names = $files['pdffiles']['name'];
    $tmp_names = $files['pdffiles']['tmp_name'];

    $files_array = array_combine($tmp_names, $names);

    // save file in uploads folder
    foreach($files_array as $tmp_folder => $image_name){
        move_uploaded_file($tmp_folder, $folder.$image_name);
    }

    return "success";
}

function downloadFile($filename) {
    $file_path = "uploads/" . $filename;

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
}
?>
