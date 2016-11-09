<?php include("common/start.php"); custom_start();

// Grab uploaded text data if it was posted from index.php
if($_POST["submit"] == "submitText"){
  // Store the list as an array in uploadGenes separated by newlines
  $_SESSION["uploadedGenes"] = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$_POST["genesTextbox"]));
  
}else if($_POST["submit"] == "submitFile"){
  // File uploader script, allows text format files
  // of size <10kb and moves them in /upload if valid
  
  // Check file size
  if ($_FILES["uploadedfile"]["size"] > 10000) {
    echo "Error, your file is too large.";
    echo '<br/><a href="../binflabs">Go back</a>';
    exit;
  }
  // Get content
  if ($_FILES['uploadedfile']['error'] == UPLOAD_ERR_OK) {
    // Store the list as an array in uploadGenes separated by newlines
    $file = file_get_contents($_FILES['uploadedfile']['tmp_name']);
    var_dump($file);
    $_SESSION["uploadedGenes"] = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$file));
    // Go back to index.php
    //header('Location: index.php');
  }else{
    echo "Error, your file could not be uploaded.";
    echo '<br/><a href="../binflabs">Go back</a>';
    exit;
  }
  // Go back to index.php
  header('Location: index.php');
}
?>
