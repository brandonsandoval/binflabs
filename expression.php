<?php include("common/start.php"); custom_start();
  // Get GET variable to set the lab/condition we are working with
  if(isset($_GET['lab']) && isset($_GET['cond'])){
    $getValid = false;
    $lab = $_GET['lab'];
    $cond = $_GET['cond'];
    $labCond = $lab.'_'.$cond;
    // Make sure the GET inputs are valid
    if($lab == 'causton'){
      if($cond == 'acid' || $cond == 'alkali' || $cond == 'h2o2' || $cond == 'heat' || $cond == 'salt' || $cond == 'sorbitol'){
        $getValid = true;
      }
    } else if($lab == 'gasch'){
      if($cond == 'diamide' || $cond == 'h2o2' || $cond == 'heat' || $cond == 'hyperosmotic' || $cond == 'hypoosmotic' || $cond == 'menadione'){
        $getValid = true;
      }
    }
    if(!$getValid){
      echo "Error, invalid GET param value";
      exit;
    }
  }else{
    // Exit if a link request did not provide a lab and condition
    echo "Error, GET params are not valid";
    exit;
  }
  // If data was uploaded and we have not grabbed the expression *.tab data
  if(isset($_SESSION["uploadedGenes"])){
  
    // For requesting download link
    if(isset($_GET['download']) && $_GET['download'] == 'true'){
      // Provide a downloadable text file
      header('Content-type: text/plain');
      header("Content-Disposition: attachment; filename=".$labCond.".tab");
      
      $line = shell_exec('head -1 data/expression/'.$lab.'_'.$cond.'.tab');
      // strip out unwanted space chars
      $lineClean = preg_replace("/  /", "", $line);
      echo $lineClean;
      foreach($_SESSION["uploadedGenesSys"] as $key => $gene){
        // Get line from .tab file that corresponds to the gene
        $line = shell_exec('egrep "'.$_SESSION["uploadedGenesSys"][$key].'" data/expression/'.$lab.'_'.$cond.'.tab');
        // strip out unwanted space chars and newlines
        $lineClean = preg_replace("/ /", "", $line);
        $lineClean = preg_replace("/\n/", "", $lineClean);
        // Change gene to current namespace
        $lineArray = explode("\t",$lineClean);
        $i = 0;
        $size = sizeof($lineArray);
        foreach($lineArray as $item){
          if($i++ == 0){
            // For first line print the correct namespace instead of the default gene
            $key = array_search($item, $_SESSION['uploadedGenesSys']);
            echo $_SESSION['uploadedGenes'.$_SESSION['namespace']][$key]."\t";
          }else if($i == $size){
            // For the last line add a newline at the end
            echo $item."\n";
          
          }else{
            // For each non-first and non-last line, and the \t delimiter.
            echo $item."\t";
          }
        }
      }
      exit; // Download success
    }
  
  
    foreach($_SESSION["uploadedGenes".$_SESSION["namespace"]] as $key => $gene){
      // Get line from .tab file that corresponds to the gene
      $line = shell_exec('egrep "'.$_SESSION["uploadedGenesSys"][$key].'" data/expression/'.$lab.'_'.$cond.'.tab');
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
            $expression[$labCond][$gene][$j] = 0;
          }else{
            $expression[$labCond][$gene][$j] = floatval($value);
          }
        }else{
          // For the first element, we store its gene name instead of its value
          $expression[$labCond][$gene][0] = $gene;
        }
        $j++;
      }
      
      $j = 0;
      // Calculate there sums across values, i.e. 0m, 20m 40m etc.
      // so it can be displayed in a bar chart.
      $expression[$labCond.'_sum'][$gene] = 0;
      foreach($expression[$labCond][$gene] as $value){
        // Skip the first value, because that is only a counter, not a value
        if($j++ != 0){
          $expression[$labCond.'_sum'][$gene] += $value;
        }
      }
    }
    // Compute the highest and lowest sum totals gene by the sum of there values
    // We will show these in a table
    $highestSum = -99999999;
    $lowestSum = 99999999;
    foreach($expression[$labCond.'_sum'] as $geneName => $geneSum){
      if($geneSum > $highestSum){
        $highestSum = $geneSum;
        $highestGeneName = $geneName;
      }
      if($geneSum < $lowestSum){
        $lowestSum = $geneSum;
        $lowestGeneName = $geneName;
      }
    }
    $expression[$labCond.'_highest'] = $highestGeneName;
    $expression[$labCond.'_lowest'] = $lowestGeneName;
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
EOT;
          if($lab == "causton"){
            if($cond == "acid"){
              echo "['Genes', '0m', '10m', '20m', '40m', '60m', '80m', '100m'],";
            }else if($cond == "alkali"){
              echo "['Genes', '0m', '10m', '20m', '40m', '60m', '80m', '100m'],";
            }else if($cond == "h2o2"){
              echo "['Genes', '0m', '10m', '20m', '40m', '60m', '120m'],";
            }else if($cond == "heat"){
              echo "['Genes', '0m', '15m', '30m', '45m', '60m', '120m'],";
            }else if($cond == "salt"){
              echo "['Genes', '0m', '15m', '30m', '45m', '60m', '120m'],";
            }else if($cond == "sorbitol"){
              echo "['Genes', '0m', '15m', '30m', '45m', '90m', '120m'],";
            }
          }else if($lab == "gasch"){
            if($cond == "diamide"){
              echo "['Genes', '5m', '10m', '20m', '30m', '40m', '50m', '60m', '90m'],";
            }else if($cond == "h2o2"){
              echo "['Genes', '10m', '20m', '30m', '40m', '50m', '60m', '80m', '100m', '120m', '160m'],";
            }else if($cond == "heat"){
              echo "['Genes', '5m', '10m', '15m', '20m', '30m', '40m', '60m', '80m'],";
            }else if($cond == "hyperosmotic"){
              echo "['Genes', '5m', '15m', '30m', '45m', '60m', '90m', '120m'],";
            }else if($cond == "hypoosmotic"){
              echo "['Genes', '5m', '15m', '30m', '45m', '60m'],";
            }else if($cond == "menadione"){
              echo "['Genes', '10m', '20m', '30m', '40m', '50m', '80m', '105m', '120m', '160m'],";
            }
          }
          foreach($expression[$labCond] as $values){
            echo json_encode($values).',';
          }
echo <<< EOT
        ]);
        var chart = new google.charts.Bar(document.getElementById('chart'));
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
    echo '<div class="inner-container"><p><b>Please <a href="../binflabs">upload</a> a list of genes before continuing..</b></p></div>';
  } else {
  // continue on if they have uploaded
    echo '<center><br/>';
    echo '<h4>'.ucfirst($lab).' - '.$cond.' levels</h4>';
    echo '<div id="chart" style="width: 900px; height: 500px;"></div><br/>';
    echo '<h4>Genes with <span class="glyphicon glyphicon-menu-up"></span>highest and <span class="glyphicon glyphicon-menu-down"></span>lowest sum total '.$cond.' levels: </h4>';
echo <<< EOT
    </center>
    <table class="table table-striped" style="width: 100%; font-size: 14px; font-weight: normal;">
      <thead>
        <tr>
EOT;
        if($lab == "causton"){
          if($cond == "acid"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>10m</th><th>20m</th><th>40m</th><th>60m</th><th>80m</th><th>100m</th>';
          }else if($cond == "alkali"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>10m</th><th>20m</th><th>40m</th><th>60m</th><th>80m</th><th>100m</th>';
          }else if($cond == "h2o2"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>10m</th><th>20m</th><th>40m</th><th>60m</th><th>120m</th>';
          }else if($cond == "heat"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>15m</th><th>30m</th><th>45m</th><th>60m</th><th>120m</th>';
          }else if($cond == "salt"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>15m</th><th>30m</th><th>45m</th><th>60m</th><th>120m</th>';
          }else if($cond == "sorbitol"){
            echo '<th>Gene</th><th>Sum total</th><th>0m</th><th>15m</th><th>30m</th><th>45m</th><th>90m</th><th>120m</th>';
          }
        }else if($lab == "gasch"){
          if($cond == "diamide"){
            echo '<th>Gene</th><th>Sum total</th><th>5m</th><th>10m</th><th>20m</th><th>30m</th><th>40m</th><th>50m</th><th>60m</th><th>90m</th>';
          }else if($cond == "h2o2"){
            echo '<th>Gene</th><th>Sum total</th><th>10m</th><th>20m</th><th>30m</th><th>40m</th><th>50m</th><th>60m</th><th>80m</th><th>100m</th><th>120m</th><th>160m</th>';
          }else if($cond == "heat"){
            echo '<th>Gene</th><th>Sum total</th><th>5m</th><th>10m</th><th>15m</th><th>20m</th><th>30m</th><th>40m</th><th>60m</th><th>80m</th>';
          }else if($cond == "hyperosmotic"){
            echo '<th>Gene</th><th>Sum total</th><th>5m</th><th>15m</th><th>30m</th><th>45m</th><th>60m</th><th>90m</th><th>120m</th>';
          }else if($cond == "hypoosmotic"){
            echo '<th>Gene</th><th>Sum total</th><th>5m</th><th>15m</th><th>30m</th><th>45m</th><th>60m</th>';
          }else if($cond == "menadione"){
            echo '<th>Gene</th><th>Sum total</th><th>10m</th><th>20m</th><th>30m</th><th>40m</th><th>50m</th><th>80m</th><th>105m</th><th>120m</th><th>160m</th>';
          }
        }
echo <<< EOT
        </tr>
      </thead>
      <tbody>
EOT;
    // List the highest sum total gene in table
    $geneHighest = $expression[$labCond."_highest"];
    echo '<tr><th><span class="glyphicon glyphicon-menu-up"></span>'.$geneHighest.'</th>';
    echo '<th>'.$expression[$labCond."_sum"][$geneHighest].'</th>';
    $i = 0;
    foreach($expression[$labCond][$geneHighest] as $value){
      if($i != 0) // Skip the first one as it is a counter
        echo '<th>'.$value.'</th>';
      $i++;
    }
    echo '</tr>';
    
    // and the lowest sum total
    $geneLowest = $expression[$labCond."_lowest"];
    echo '<tr><th><span class="glyphicon glyphicon-menu-down"></span>'.$geneLowest.'</th>';
    echo '<th>'.$expression[$labCond."_sum"][$geneLowest].'</th>';
    $i = 0;
    foreach($expression[$labCond][$geneLowest] as $value){
      if($i != 0) // Skip the first one as it is a counter
        echo '<th>'.$value.'</th>';
      $i++;
    }
    echo '</tr>';

echo <<< EOT
      </tbody>
    </table>
    <h6>*(Note: NULLs and Blanks in dataset are treated as 0s in table/chart)</h6>
    <p>Download link for all uploaded gene table results</p>
EOT;
    echo '<a href="expression.php?download=true&lab='.$lab.'&cond='.$cond.'" class="btn btn-default" role="button">Download '.$labCond.'.tab</a>';

  } // End of: if(!isset($_SESSION["uploadedGenes"]))

  echo '<br/><br/><br/><br/></div><!-- /.container -->';
  include 'common/footer.php';
  echo '</body></html>';
?>

