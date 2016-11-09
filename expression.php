<?php include("common/start.php"); custom_start();

  // Grab uploaded data if it was posted from index.php
  if(isset($_POST["submit"]) && ($_POST["submit"] == "Upload")){
    echo "<p>POST DATA:</p>";
    var_dump($_POST);
    // Store the list as an array in uploadGenes separated by newlines
    $_SESSION["uploadedGenes"] = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$_POST["genesTextbox"]));
  }
?>

<html lang="en">
  <?php include 'common/header.php' ?>

  <body>
    <?php include 'common/navbar.php' ?>

    <div class="container">

  <?php
    // Display notice if user has not uploaded anything
    if(!isset($_SESSION["uploadedGenes"])){
      echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of genes before continuing..</b></p></div>';
    } else {
    // continue on if they have uploaded
  ?>
      <h3>Causton</h3><br/>

      <table class="table table-striped" style="width: 100%;">
        <thead>
          <tr>
            <th>Gene</th>
            <th>Col 1</th>
            <th>Col 2</th>
            <th>Col 3</th>
            <th>Col 4</th>
            <th>Col 5</th>
          </tr>
        </thead>

        <tbody>
        <?php
          foreach ($_SESSION["uploadedGenes"] as $value) {
            echo '<th>'.$value.'</th>';
        ?>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            </tr>
          <?php
          } // End of foreach uploadedGenes loop
          ?>
        </tbody>
      </table>

      <h3>Gasch</h3><br/>

      <table class="table table-striped" style="width: 100%;">
        <thead>
          <tr>
            <th>Gene</th>
            <th>Col 1</th>
            <th>Col 2</th>
            <th>Col 3</th>
            <th>Col 4</th>
            <th>Col 5</th>
          </tr>
        </thead>

        <tbody>
        <?php
          foreach ($_SESSION["uploadedGenes"] as $value) {
            echo '<th>'.$value.'</th>';
        ?>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            </tr>
          <?php
          } // End of foreach uploadedGenes loop
          ?>
        </tbody>
      </table>

      <?php
        } // End of: if(!isset($_SESSION["uploadedGenes"]))
      ?>

    </div><!-- /.container -->

  <?php include 'common/footer.php' ?>
  </body>
</html>
