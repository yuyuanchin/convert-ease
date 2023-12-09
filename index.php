<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/upload.php';
require_once __DIR__ . '/inc/flash.php';
require_once __DIR__.'/inc/functions.php';

if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'convertease.com') !== false) {
    require_once __DIR__.'/inc/functions.php';
    deleteFile();
}

//if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
//    require_once __DIR__.'/upload.php';
//
//}

//session_start();

if(isset($_POST['uploadFile'])) {
    require_once __DIR__ . '/inc/flash.php';
    require_once __DIR__.'/inc/functions.php';
    require_once __DIR__.'/upload.php';
    uploadFile();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>ConvertEase | PDF to TXT Converter</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
</head>

<body>
<header>
    <!-- Logo -->
    <div>
        <a class="logo" href="/">
            <img src="/images/logo-full.png" alt="logo">
        </a>
    </div>

    <!-- PDF to TXT / TXT to PDF -->
    <div class="dropdown">
        <button>
            PDF to TXT
            <span class="material-symbols-outlined">
          expand_more
        </span>
        </button>
        <div class="dropdown-menu">
            <div class="dropdown-menu-item">
                <a href="txt-to-pdf.html">TXT to PDF</a>
            </div>
        </div>
    </div>

    <!-- Login & Sign Up -->
    <div class="login">
        <button id="login">Login</button>
        <button id="signup">Sign Up</button>
    </div>
</header>

<main>
    <img id="background-image" src="./images/bg-7.png" alt="Background image">
    <div class="main">
        <h1>PDF to TXT File Converter</h1>
        <div class="container">
            <!-- File Upload -->
            <div class="card file-upload" id="drop-area">
                <div class="form-container">
                    <img src="images/upload-files.png" alt="Upload files" width="100px">
                    <span id="drag-drop">Drag & Drop</span>
                    <form id="form" action="" method="post" enctype="multipart/form-data">
                        <label for="file">or <span>Browse</span></label>
                        <input type="hidden" name="conversionType" value="pdf2txt">
                        <input class="hidden-input" id="file" type="file" name="files[]" multiple accept=".pdf">
                    </form>
                    <p id="max-file-size">Maximum file size: 5MB</p>
                    <?php flash('upload') ?>
                </div>
            </div>
            <!-- Uploaded Files -->
            <div class="card" id="file-list">
                <div id="files-container">
                    <!-- <div class="file">
                      <div class="file-name">
                        <img src="/images/file.png" alt="File icon" width="25px" height="25px">
                        <p>file_1.txt</p>
                      </div>
                      <div class="file-size">
                        <p>3MB</p>
                        <span class="material-symbols-outlined">
                          cancel
                        </span>
                      </div>
                    </div> -->
                </div>
                <div class="submit">
                    <button form="form" type="submit" name="uploadFile">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file');
        const fileList = document.getElementById('file-list');
        const filesContainer = document.getElementById('files-container');

        dropArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', function () {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', function (e) {
            e.preventDefault();
            dropArea.classList.remove('dragover');

            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        fileInput.addEventListener('change', function () {
            const files = fileInput.files;
            handleFiles(files);
        });

        function formatFileSize(bytes) {
            const kilobytes = bytes / 1024;
            if (kilobytes < 1000) {
                return kilobytes.toFixed(1) + ' KB';
            } else {
                const megabytes = kilobytes / 1024;
                return megabytes.toFixed(2) + ' MB';
            }
        }

        function handleFiles(files) {
            console.log(files);
            if (files.length > 0) {
                const allowedTypes = ['application/pdf'];
                const isValidFileType = Array.from(files).every(file => allowedTypes.includes(file.type));

                if (isValidFileType) {
                    const filesArray = Array.from(files);
                    fileList.style.display = 'flex';

                    filesArray.forEach(file => {
                        console.log('passed');
                        const fileContainer = document.createElement('div');
                        fileContainer.classList.add('file');
                        const fileNameContainer = document.createElement('div');
                        fileNameContainer.classList.add('file-name');
                        const fileIcon = document.createElement('img');
                        fileIcon.src = 'images/file.png';
                        fileIcon.alt = 'File icon';
                        fileIcon.width = 25;
                        fileNameContainer.appendChild(fileIcon);
                        const fileName = document.createElement('p');
                        fileName.textContent = file.name;
                        fileNameContainer.appendChild(fileName);
                        fileContainer.appendChild(fileNameContainer);
                        const fileSizeContainer = document.createElement('div');
                        fileSizeContainer.classList.add('file-size');
                        const fileSize = document.createElement('p');
                        fileSize.textContent = formatFileSize(file.size);
                        const deleteIcon = document.createElement('span');
                        deleteIcon.classList.add('material-symbols-outlined');
                        deleteIcon.textContent = 'cancel';
                        fileSizeContainer.appendChild(fileSize);
                        fileSizeContainer.appendChild(deleteIcon);
                        fileContainer.appendChild(fileSizeContainer);
                        filesContainer.appendChild(fileContainer);
                    });
                } else {
                    alert('Invalid file type. Please upload only PDF files.');
                }
            }
        }
    });
</script>