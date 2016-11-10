<?php include("common/start.php"); custom_start();
  // If data is uploaded and we have not grabbed the expression .tab data
  if(isset($_SESSION["uploadedGenes"]) && $_SESSION["computedExpression"] === false){
    foreach($_SESSION["uploadedGenes"] as $gene){
      // Get line from .tab file that corresponds to the gene
      $line = shell_exec('egrep "'.$gene.'" data/expression/causton_acid.tab');
      // strip out unwanted space chars
      $lineClean = preg_replace("/ /", "", $line);
      
      // store tab delimited line into an array
      $values = explode("\t",$lineClean);
      $j = 0;
      foreach($values as $value){
        if($j != 0){
          // convert array to floats, convert nulls to 0's
          // If null
          if($value[0] == 'N'){
            $_SESSION['expression_causton_acid'][$gene][$j] = 0;
          }else{
            $_SESSION['expression_causton_acid'][$gene][$j] = floatval($value);
          }
        }else{
          // For the first element, we store its gene name instead of its value
          $_SESSION['expression_causton_acid'][$gene][0] = $values[0];
        }
        $j++;
      }
      
      $j = 0;
      // Calculate there sums across values, i.e. 0m, 20m 40m etc.
      // so it can be displayed in a bar chart.
      $_SESSION['expression_causton_acid_sum'][$gene] = 0;
      foreach($_SESSION['expression_causton_acid'][$gene] as $value){
        // Skip the first value, because that is only a counter, not a value
        if($j++ != 0){
          $_SESSION['expression_causton_acid_sum'][$gene] += $value;
        }
      }
    }
    // Compute the highest and lowest sum totals gene by the sum of there values
    // We will show these in a table
    $highestSum = 0;
    $lowestSum = 99999999;
    foreach($_SESSION['expression_causton_acid_sum'] as $geneName => $geneSum){
      if($geneSum > $highestSum){
        $highestSum = $geneSum;
        $highestGeneName = $geneName;
      }
      if($geneSum < $lowestSum){
        $lowestSum = $geneSum;
        $lowestGeneName = $geneName;
      }
    }
    $_SESSION['expression_causton_acid_highest'] = $highestGeneName;
    $_SESSION['expression_causton_acid_lowest'] = $lowestGeneName;
    // Set this true, so we don't have to recompute if the data doesn't change.
    $_SESSION["computedExpression"] = true;
  }

  echo '<html lang="en">';
  include 'common/header.php';
  //print_session();
  
  // If genes are uploaded we can import the scripts for graphing
  if(isset($_SESSION["uploadedGenes"])){
echo <<< EOT
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Genes', '0m', '10m', '20m', '40m', '60m', '80m', '100m'],
EOT;
          foreach($_SESSION['expression_causton_acid'] as $values){
            echo json_encode($values).',';
          }
echo <<< EOT
        ]);
        var chart = new google.charts.Bar(document.getElementById('causton_acid'));
        chart.draw(data);
      }
    </script>
    <body>
EOT;
  }
  include 'common/navbar.php';
  echo '<div class="container">';
  
  // Display notice if user has not uploaded anything
  if(!isset($_SESSION["uploadedGenes"])){
    echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of genes before continuing..</b></p></div>';
  } else {
  // continue on if they have uploaded
echo <<< EOT
    <center><br/>
      <h4>Causton - Acid levels</h4>
      <div id="causton_acid" style="width: 900px; height: 500px;"></div><br/>
      <h4>Genes with <span class="glyphicon glyphicon-menu-up"></span>highest and <span class="glyphicon glyphicon-menu-down"></span>lowest sum total acid levels: </h4>
    </center>
    <table class="table table-striped" style="width: 100%; font-size: 14px; font-weight: normal;">
      <thead>
        <tr>
          <th><b>Gene</b></th>
          <th>Sum total</th>
          <th>0m</th>
          <th>10m</th>
          <th>20m</th>
          <th>40m</th>
          <th>60m</th>
          <th>80m</th>
          <th>100m</th>
        </tr>
      </thead>
      <tbody>
EOT;
    // List the highest sum total gene in table
    $geneHighest = $_SESSION["expression_causton_acid_highest"];
    echo '<tr><th><span class="glyphicon glyphicon-menu-up"></span>'.$geneHighest.'</th>';
    echo '<th>'.$_SESSION["expression_causton_acid_sum"][$geneHighest].'</th>';
    $i = 0;
    foreach($_SESSION["expression_causton_acid"][$geneHighest] as $value){
      if($i != 0) // Skip the first one as it is a counter
        echo '<th>'.$value.'</th>';
      $i++;
    }
    echo '</tr>';
    
    // and the lowest sum total
    $geneLowest = $_SESSION["expression_causton_acid_lowest"];
    echo '<tr><th><span class="glyphicon glyphicon-menu-down"></span>'.$geneLowest.'</th>';
    echo '<th>'.$_SESSION["expression_causton_acid_sum"][$geneLowest].'</th>';
    $i = 0;
    foreach($_SESSION["expression_causton_acid"][$geneLowest] as $value){
      if($i != 0) // Skip the first one as it is a counter
        echo '<th>'.$value.'</th>';
      $i++;
    }
    echo '</tr>';

echo <<< EOT
      </tbody>
    </table>
    <h6>*(Note: NULLs in dataset are treated as 0)</h6>
    <button type="button" class="btn btn-primary">Download  all acid results</button>

EOT;
  } // End of: if(!isset($_SESSION["uploadedGenes"]))

  echo '<br/><br/><br/><br/></div><!-- /.container -->';
  include 'common/footer.php';
  echo '</body></html>';
?>

