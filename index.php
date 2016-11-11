<?php include("common/start.php"); custom_start();

?>

<html lang="en">
  <?php include 'common/header.php' ?>

  <body>
    <?php include 'common/navbar.php' ?>

    <div class="container">
    
      <!-- Help box -->
      <div class="modal fade" id="myHelp" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Help: Uploading genes</h4>
            </div>
            <div class="modal-body">
              <p>Only Saccharomyces Cerevisiae (Yeast) genes are accepted, any other genes of invalid format or is not in our database will be ignored. They must be in ORF format, for example: <b>&quotYAL014C&quot</b>. Accepted characters are &quotA-Z&quot, &quota-z&quot, &quot0-9&quot and &quot-&quot.</p>
              <p>Uploading text or files to the gene list will always <b>add</b> to the current gene list, if you wish to start new, then press &quotRemove all&quot at the bottom of the gene list</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
  
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
          echo '</ul><a href="delete.php" class="btn btn-danger" role="button">Remove all</a></div>';
        }
      ?>
      <div class="inner-container well">
        <form action="upload.php" method="POST">
          <div class="form-group">
            <p><b>Type a list of genes below:</b> (separated by newlines)</p>
            <textarea type="text" class="form-control" rows="10" id="genesTextbox" name="genesTextbox"></textarea>
          </div>
          <button class="btn btn-primary" type="submit" value="submitText" name="submit">Upload</button>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myHelp">Help</button>
        </form>
      </div>
    
      <div class="inner-container well">
        <p><b>OR</b></p>
        <p><b>Upload a list of genes</b> (10kb limit, only .txt files with genes separated by newlines)</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
          <input class="btn btn-default" type="file" name="uploadedfile" id="uploadedfile">
          <br/>
          <button class="btn btn-primary" type="submit" value="submitFile" name="submit">Upload</button>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myHelp">Help</button>
        </form>
      </div>

    </div><!-- /.container -->

  <?php include 'common/footer.php' ?>
  </body>
</html>
