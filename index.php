<?php include("common/start.php"); custom_start();

?>

<html lang="en">
  <?php include 'common/header.php' ?>

  <body>
    <?php include 'common/navbar.php' ?>

    <div class="container">

      <div class="inner-container well">
        <p><b>Upload a list of genes (10kb limit, only .txt files)</b></p>
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <input class="btn btn-default" type="file" name="fileToUpload" id="fileToUpload">
          <br/>
        <div class="form-group">
          <p><b>OR </b></p>
          <p><b>Type a list of genes below:</b></p>
          <textarea class="" rows="10" id="genes""></textarea>
        </div>
        <input class="btn btn-default" type="submit" value="Upload" name="submit">
        </form>
      </div>

      <h3>Data</h3><br/>
			<table class="table table-striped" style="width: 100%;">
				<thead>
					<tr>
						<th>Col 1</th>
						<th>Col 2</th>
						<th>Col 3</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>


    </div><!-- /.container -->

  <?php include 'common/footer.php' ?>
  </body>
</html>
