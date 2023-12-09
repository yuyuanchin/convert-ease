<?php
session_start();
require_once __DIR__ . '/inc/flash.php';
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
            <div>
              <img src="images/upload-files.png" alt="Upload files" width="100px">
              <span id="drag-drop">Drag & Drop</span>
              <p id="or">or</p>
              <form id="form" action="convert.php" method="post" enctype="multipart/form-data">
                <label for="file"><span id="file-selector">Browse Files</span></label>
                <input class="hidden-input" id="file" type="file" name="files[]" multiple accept=".pdf">
              </form>
              <p id="max-file-size">Maximum file size: 5MB</p>
              <?php flash('upload') ?>
            </div>
            <div>
              <div class="drop-here">Drop Here</div>
            </div>
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
            <button form="form" type="submit" id="submit">
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>

<script src="script.js"></script>