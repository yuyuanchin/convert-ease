<?php

//echo php_ini_loaded_file();

// Messages associated with upload error code
const MESSAGES = [
    UPLOAD_ERR_OK => 'File uploaded successfully',
    UPLOAD_ERR_INI_SIZE => 'File is too big to upload',
    UPLOAD_ERR_FORM_SIZE => 'File is too big to upload',
    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
    UPLOAD_ERR_CANT_WRITE => 'File is failed to save to disk.',
    UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server',
];

// Return mime type
function get_mime_type($filename)
{
    $info = finfo_open(FILEINFO_MIME_TYPE);
    if (!$info) {
        return false;
    }

    $mime_type = finfo_file($info, $filename);
    finfo_close($info);

    return $mime_type;
}

function format_filesize( $bytes,  $decimals = 2)
{
    $units = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $units[(int) $factor];
}

// Redirect user with session based flash message
function redirect_with_message( $message,  $type = FLASH_ERROR,  $name = 'upload',  $location = 'index.php')
{
    flash($name, $message, $type);
    //header("Location: $location", true, 303);
    //exit;
}

function format_messages( $title, array $messages)
{
    $message = "<p>$title</p>";
    $message .= '<ul>';
    foreach ($messages as $key => $value) {
        $message .= "<li>$value</li>";
    }
    $message .= '<ul>';

    return $message;
}

function convertFile($files,$conversionType) {

    $uploadDir = __DIR__ . '/uploads/';
    $downloadDir = __DIR__ . '/downloads/';

    $file_count = count($files['name']);

    for ($i = 0; $i < $file_count; $i++) {
        $filename = $files['name'][$i];
        //$mime_type = get_mime_type($tmp);

        $outputFile = $downloadDir . pathinfo($filename, PATHINFO_FILENAME) . "." . ($conversionType === "pdf2txt" ? "txt" : "pdf");

        // Additional variables (if necessary)
        $inputFile = $uploadDir . $filename;

        $javaCommand = "/usr/bin/java -cp /var/www/html/ConvertEase/PDFConverter.jar:/var/www/html/ConvertEase/lib/pdfbox-app-2.0.30.jar NewPDFConverter \"$inputFile\" \"$outputFile\" $conversionType";

        // Execute the Java command
        exec($javaCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            // Log or handle the error
            //error_log("Conversion failed for file $filename: " . implode("\n", $output));
            echo "Conversion failed for file $filename. Please check the error logs for more details.";
        }
    }
}

function deleteFile()
{
    // Delete files in the "uploads" directory
    //$uploadDirectory = 'uploads/';
    $files = glob(UPLOAD_DIR . '*');
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
    //$downloadDirectory = 'downloads/';
    $files = glob(DOWNLOAD_DIR . '*');
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
