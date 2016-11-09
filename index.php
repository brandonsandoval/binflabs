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
          echo '<p><b>Current gene list:</b></p>';
          echo '<ul>';
          foreach ($_SESSION["uploadedGenes"] as $value) {
            echo '<li>'.$value.'</li>';
          }
          echo '</ul></div>';
        }
      ?>

      <div class="inner-container well">
        <form action="expression.php" name="geneSubmitText" method="POST">
          <div class="form-group">
            <p><b>Type a list of genes below:</b> (separated by newlines)</p>
            <textarea type="text" class="form-control" rows="10" id="genesTextbox" name="genesTextbox"></textarea>
          </div>
          <input class="btn btn-primary" type="submit" value="Upload" name="submit">
        </form>
      </div>

      <div class="inner-container well">
        <p><b>OR</b></p>
        <p><b>Upload a list of genes (10kb limit, only .txt files)</b></p>
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <input class="btn btn-default" type="file" name="fileToUpload" id="fileToUpload">
          <br/>
          <input class="btn btn-primary" type="submit" value="Upload" name="submit">
        <h6>*does not work at the moment, please use textbox above)</h6>
        </form>
      </div>

    </div><!-- /.container -->

  <?php include 'common/footer.php' ?>
  </body>
</html>
