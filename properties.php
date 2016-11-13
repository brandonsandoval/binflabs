<?php include("common/start.php"); custom_start();
  // Download links
  if(isset($_SESSION["uploadedGenes"]) && isset($_GET['download']) && $_GET['download'] == 'true'){
    if ($_SESSION["namespace"] == 'ID') $field = 1;
    if ($_SESSION["namespace"] == 'Sys') $field = 2;

    $relevant_properties = array();
    $contents = "";
    foreach ($_SESSION["uploadedGenesStd"] as $gene) {
      $relevant_properties = array_filter(array_unique(array_merge($relevant_properties, explode("\n", shell_exec("grep -P \"\\t$gene\\t\" data/properties/sgd_pathways.tab")))));
      $contents .= shell_exec("grep -P \"\\t$gene\\t\" data/properties/sgd_pathways.tab");
    }
    
    // For requesting download link
    if(isset($_GET['download']) && $_GET['download'] == 'true'){
      // Provide a downloadable text file
      header('Content-type: text/plain');
      header('Content-Disposition: attachment; filename="properties.tab');
      echo $contents;
      exit; // Download success
    }
  }
?>

<html lang="en">
  <?php include 'common/header.php' ?>
  <body>
    <?php include 'common/navbar.php'; ?>
    
    <div class="container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<div class="inner-container"><p><b>Please <a href="../binflabs">upload</a> a list of genes before continuing..</b></p></div>';
        } else {
          if ($_SESSION["namespace"] == 'ID') $field = 1;
          if ($_SESSION["namespace"] == 'Sys') $field = 2;

          $relevant_properties = array();
          foreach ($_SESSION["uploadedGenesStd"] as $gene) {
            $relevant_properties = array_filter(array_unique(array_merge($relevant_properties, explode("\n", shell_exec("grep -P \"\\t$gene\\t\" data/properties/sgd_pathways.tab")))));
          }
echo <<< EOT
          <br>
          <div class="form-group pull-right">
            <input type="text" class="search form-control" placeholder="Search">
          </div>
          <span class="counter pull-right"></span>
          <h2>Gene properties and pathways</h2>
          <table class="table table-hover results">
            <thead>
              <tr>
                <th>Gene name</th>
                <th>Biochemical Pathway</th>
                <th>Enzyme name</th>
                <th>Enzyme Commission identifier</th>
                <th>References</th>
              </tr>
            </thead>
            <tbody>
EOT;
          
          foreach ($relevant_properties as $property) {
            preg_match('/^(.*?)\t(.*?)\t(.*?)\t(.*?)\t(.*?)$/', $property, $match);
            $biochem_path = $match[1];
            $enzyme = $match[2];
            $ec_number = $match[3];
            $gene_name = $match[4];
            $reference = $match[5];
            if ($_SESSION["namespace"] != 'Std') $gene_name = trim(shell_exec("egrep \"$gene_name$\" data/orf2std.tab | cut -f" . $field));
            if (empty($gene_name)) $gene_name = $match[4];
            echo <<< EOT
              <tr>
                <td>$gene_name</td>
                <td>$biochem_path</td>
                <td>$enzyme</td>
                <td>$ec_number</td>
                <td>$reference</td>
              </tr>
EOT;
          }
          
          echo <<< EOT
            </tbody>
          </table>
          <a href="properties.php?download=true" class="btn btn-default" type="submit">Download pathway information</a>
EOT;
        }
      ?>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="properties/sgd_pathways-scripts.js"></script>
  <?php include 'common/footer.php' ?>
  </body>
</html>
