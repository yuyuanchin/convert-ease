<?php

session_start();

require_once __DIR__ . '/inc/flash.php';
require_once __DIR__ . '/inc/functions.php';

const ALLOWED_FILES = [
  'application/pdf' => 'pdf',
];

const MAX_SIZE = 5 * 1024 * 1024; // 5MB

const UPLOAD_DIR = __DIR__ . '/uploads';


$is_post_request = strtolower($_SERVER['REQUEST_METHOD']) === 'post';
$has_files = isset($_FILES['file']);

if (!$is_post_request || !$has_files) {
  redirect_with_message('Invalid file upload operation', FLASH_ERROR);
}

$files = $_FILES['file'];
$file_count = count($files['name']);

// Validation
$errors = [];
for ($i = 0; $i < $file_count; $i++) {
  // Get uploaded file info
  $status = $files['error'][$i];
  $filename = $files['name'][$i];
  $tmp = $files['tmp_name'][$i];

  // If error occurs
  if ($status !== UPLOAD_ERR_OK) {
    $errors[$filename] = MESSAGES[$status];
    continue;
  }
  // Validate file size
  $filesize = filesize($tmp);

  if ($filesize > MAX_SIZE) {
    // Construct error message
    $message = sprintf("The file %s is %s which is greater than the allowed size %s",
      $filename,
      format_filesize($filesize),
      format_filesize(MAX_SIZE));

    $errors[$filesize] = $message;
    continue;
  }

  // Validate file type
  if (!in_array(get_mime_type($tmp), array_keys(ALLOWED_FILES))) {
    $errors[$filename] = "The file $filename is allowed to upload";
  }
}

if ($errors) {
  redirect_with_message(format_messages('The following errors occurred:', $errors), FLASH_ERROR);
}

// // Move files
// for ($i = 0; $i < $file_count; $i++) {
//   $filename = $files['name'][$i];
//   $tmp = $files['tmp_name'][$i];
//   $mime_type = get_mime_type($tmp);

//   // Set filename as basename + extension
//   $uploaded_file = pathinfo($filename, PATHINFO_FILENAME) . '.' . ALLOWED_FILES[$mime_type];
//   // New filepath
//   $filepath = UPLOAD_DIR . '/' . $uploaded_file;
//   // Move file to upload dir
//   $success = move_uploaded_file($tmp, $filepath);
//   if (!$success) {
//     $errors[$filename] = "The file $filename was failed to move.";
//   }
// }

if (!empty($_FILES['file'])) {
  $targetDir = 'uploads/';
  $filename = basename($_FILES['file']['name']);
  $targetFilePath = $targetDir . $filename;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
    echo 'File Uploaded';
  }
}

$errors ?
  redirect_with_message(format_messages('The following errors occurred:', $errors), FLASH_ERROR) :
  redirect_with_message('All the files were uploaded successfully.', FLASH_SUCCESS);

?>