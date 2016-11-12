<?php include("common/start.php"); custom_start();
?>

<html lang="en">
  <?php include 'common/header.php' ?>
  <body>
    <?php include 'common/navbar.php' ?>
    
    <div class="container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<div class="inner-container"><p><b>Please <a href="../binflabs">upload</a> a list of genes before continuing..</b></p></div>';
        }
      ?>
    </div>
  
  <?php include 'common/footer.php' ?>
  </body>
</html>
