<?php include("common/start.php"); custom_start();

// Grab uploaded text data if it was posted from index.php
if($_POST["submit"] == "submitText"){
  // Store the list as an array in uploadGenesRaw separated by newlines
  $uploadedGenesRaw = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$_POST["genesTextbox"]));
  
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
    $uploadedGenesRaw = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$file));
  }else{
    echo "Error, your file could not be uploaded.";
    echo '<br/><a href="../binflabs">Go back</a>';
    exit;
  }
}else{
  header('Location: ../binflabs');
  exit;
}

// Setup namespace (possible values: ID, Sys, Std) (default: Sys)
if(!isset($_SESSION["namespace"])){
  $_SESSION["namespace"] = 'Sys';
}

// Check uploadedGenesRaw for valid gene names and store them in uploadedGenes
foreach($uploadedGenesRaw as $gene){
  // Only accept alpha-numeric and "(", ")", "-" characters
  $acceptable = array('-', '_', '(', ')', '.');
  if(ctype_alnum(str_replace($acceptable, '', $gene))){
    // First check if we already have uploaded that gene
    if(isset($_SESSION["uploadedGenes"])){
      if(in_array($gene, $_SESSION["uploadedGenesID"], true) ||
         in_array($gene, $_SESSION["uploadedGenesSys"], true) ||
         in_array($gene, $_SESSION["uploadedGenesStd"], true)){
        continue;
      }
    }
    // Escape these genes with special chars to be used by grep commands
    $escgene = $gene;
    $escgene = str_replace("(", "\\(", $escgene);
    $escgene = str_replace(")", "\\)", $escgene);
    $escgene = str_replace(".", "\\.", $escgene);
    $escgene = str_replace("-", "\\-", $escgene);
    // We are going to check if the input is a valid id, systematic or standard name
    $lineID = null;
    $lineSys = null;
    $lineStd = null;
    // Slightly different grep command for Linux/Mac OSs
    if(php_uname('s') == "Linux"){
      $lineID = exec('grep -P "^'.$escgene.'\\t" data/orf2std.tab');
      $lineSys = exec('grep -P "\\t'.$escgene.'\\t" data/orf2std.tab');
      $lineStd = exec('grep -P "\\t'.$escgene.'$" data/orf2std.tab');
    }else{
      $lineID = exec('grep "^'.$escgene.'\\t" data/orf2std.tab');
      $lineSys = exec('grep "\\t'.$escgene.'\\t" data/orf2std.tab');
      $lineStd = exec('grep "\\t'.$escgene.'$" data/orf2std.tab');
    }
    
    // Initalize arrays if we have a valid line and it does not exist
    if($lineID || $lineSys || $lineStd){
      if(!isset($_SESSION["uploadedGenes"])){
        $_SESSION["uploadedGenesID"] = array();
        $_SESSION["uploadedGenesSys"] = array();
        $_SESSION["uploadedGenesStd"] = array();
      }
    }
    // Add the correct formatted gene to the list
    if($lineID){
      if(preg_match("/^".$escgene."\t(.*)\t(.*)$/", $lineID, $match)){
        $_SESSION["uploadedGenes"] = true;
        array_push($_SESSION["uploadedGenesID"], $gene);
        if($match[1] == "")
          array_push($_SESSION["uploadedGenesSys"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesSys"], $match[1]);
        if($match[2] == "")
          array_push($_SESSION["uploadedGenesStd"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesStd"], $match[2]);
      }else{
        echo "Input error";
        session_destroy();
        exit;
      }
    }else if($lineSys){
      if(preg_match("/^(.*)\t".$escgene."\t(.*)$/", $lineSys, $match)){
        $_SESSION["uploadedGenes"] = true;
        if($match[1] == "")
          array_push($_SESSION["uploadedGenesID"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesID"], $match[1]);
        array_push($_SESSION["uploadedGenesSys"], $gene);
        if($match[2] == "")
          array_push($_SESSION["uploadedGenesStd"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesStd"], $match[2]);
      }else{
        echo "Input error";
        session_destroy();
        exit;
      }
    }else if($lineStd){
      if(preg_match("/^(.*)\t(.*)\t".$escgene."$/", $lineStd, $match)){
        $_SESSION["uploadedGenes"] = true;
        if($match[1] == "")
          array_push($_SESSION["uploadedGenesID"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesID"], $match[1]);
        if($match[2] == "")
          array_push($_SESSION["uploadedGenesSys"], "NO-NAME");
        else
          array_push($_SESSION["uploadedGenesSys"], $match[2]);
        array_push($_SESSION["uploadedGenesStd"], $gene);
      }else{
        echo "Input error";
        session_destroy();
        exit;
      }
    }
  }
}

// Go back to index.php
header('Location: ../binflabs');
?>

