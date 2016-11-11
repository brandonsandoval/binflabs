<?php include("common/start.php"); custom_start();

// Grab uploaded text data if it was posted from index.php
if($_POST["submit"] == "submitText"){
  // Store the list as an array in uploadGenesRaw separated by newlines
  $_SESSION["uploadedGenesRaw"] = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$_POST["genesTextbox"]));
  
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
    $_SESSION["uploadedGenesRaw"] = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$file));
  }else{
    echo "Error, your file could not be uploaded.";
    echo '<br/><a href="../binflabs">Go back</a>';
    exit;
  }
}else{
  header('Location: index.php');
  exit;
}

// Check uploadedGenesRaw for valid gene names and store them in uploadedGenes
foreach($_SESSION["uploadedGenesRaw"] as $gene){
  // Only accept alpha-numeric and "(", ")", "-" characters
  $acceptable = array('-');
  if(ctype_alnum(str_replace($acceptable, '', $gene))){
    $line = null;
    $line = exec('grep -P "\\t'.$gene.'\\t" data/orf2std.tab');
    // If grep passes, then we must have a valid gene
    if($line){
      // Create the array if it does not exist
      if(!isset($_SESSION["uploadedGenes"])){
        $_SESSION["uploadedGenes"] = array();
      }
      array_push($_SESSION["uploadedGenes"], $gene);
    }
  }
  // $line = shell_exec('egrep "'.$gene.'" data/expression/'.$lab.'_'.$cond.'.tab');
}

// Go back to index.php
header('Location: index.php');
?>














