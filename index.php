<?php include("common/start.php"); custom_start();

?>

<html lang="en">
  <?php include 'common/header.php' ?>

  <body>
    <?php include 'common/navbar.php' ?>

    <div class="container">

      <?php
        // Show a list of already uploaded genes
        if(isset($_SESSION["uploadedGenes"])) {
          echo '<div class="inner-container well">';
          echo '<p><b>Current gene list:</b>';
          echo '<br/>You can now go to Expression, Interactions... in the navbar above</p>';
          echo '<ul>';
          foreach ($_SESSION["uploadedGenes"] as $value) {
            echo '<li>'.$value.'</li>';
          }
          echo '</ul></div>';
        }
      ?>
      <div class="inner-container well">
        <form action="upload.php" method="POST">
          <div class="form-group">
            <p><b>Type a list of genes below:</b> (separated by newlines)</p>
            <textarea type="text" class="form-control" rows="10" id="genesTextbox" name="genesTextbox"></textarea>
          </div>
          <button class="btn btn-primary" type="submit" value="submitText" name="submit">Upload</button>
        </form>
      </div>
    
      <div class="inner-container well">
        <p><b>OR</b></p>
        <p><b>Upload a list of genes</b> (10kb limit, only .txt files with genes separated by newlines)</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
          <input class="btn btn-default" type="file" name="uploadedfile" id="uploadedfile">
          <br/>
          <button class="btn btn-primary" type="submit" value="submitFile" name="submit">Upload</button>
        </form>
      </div>

    </div><!-- /.container -->

  <?php include 'common/footer.php' ?>
  </body>
</html>
