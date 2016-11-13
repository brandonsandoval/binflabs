<?php include("common/start.php"); custom_start();
  session_destroy();
  header('Location: index.php');
  exit;
?>
