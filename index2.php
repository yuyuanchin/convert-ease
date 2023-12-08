<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require "functions.php";

//reload server will clear the folder
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    deleteFiles();

$response = "";

if(isset($_FILES['pdfFiles'])){
    $response = uploadPDFFiles2($_FILES);
}

if(isset($_POST['download'])){
    $filename = isset($_POST['file']) ? $_POST['file'] : '';
    downloadFile($filename);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload a File</title>
</head>
<body>
<h2>This is HTML page</h2>
</body>
<!-- Upload form -->
<!-- action -> pass to which page -->
<form action="" method="post" enctype="multipart/form-data">
    <h1> Choose files to upload </h1>
    <!-- Choose more than 1 file -->
    <input type="file" name="pdfFiles[]" accept=".pdf" multiple="multiple"/>
    <input type="hidden" name="conversionType" value="pdf2txt">
    <button type="submit" name="upload">Upload file </button>

    <?php
    if($response == "success"){
        ?>

        <p class = "success">Files uploaded successfully</p>
        <?php
    }else{
        ?>

        <p class = "error"><?php echo $response; ?></p>
        <?php
    }
    ?>
</form>

<?php
$files = scandir("download");

for ($a = 2; $a < count($files); $a++){
    ?>
    <p>
        <?php echo $files[$a]; ?>

    <form method="post" action="" style="display:inline;">
        <input type="hidden" name="file" value="<?php echo $files[$a]; ?>">
        <button type="submit" name="download">Download</button>
    </form>
    </p>
    <?php
}
?>
</html>
