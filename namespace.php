<?php include("common/start.php"); custom_start();

  // Changes the namespace from ID, systematic or standard name
  if(isset($_GET['namespace'])){
    $getValid = false;
    $namespace = $_GET['namespace'];
    // Make sure the GET inputs are valid
    if($namespace == 'ID' || $namespace == 'Sys' || $namespace == 'Std'){
      $_SESSION["namespace"] = $namespace;
      $getValid = true;
    }
    
    // Exit if namespace get variable is not a valid namespace
    if(!$getValid){
      echo "Error, invalid GET param value";
      exit;
    }
  }else{
    // Exit if a link request did not provide a namespace get variable
    echo "Error, GET params are not valid";
    exit;
  }

// Go back to index.php
header('Location: ../binflabs');
?>

