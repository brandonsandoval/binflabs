<?php include("common/start.php"); custom_start(); ?>

<html lang="en">
  <?php include 'common/header.php' ?>
  <body>
    <?php include 'common/navbar.php' ?>
    
    <div class="container">
      <div class="inner-container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<p><b>Please <a href="../binflabs">upload</a> a list of genes before continuing..</b></p>';
        }else{
          echo '<p>Click on each link to show there Gene Ontology (GO) links</p>';
          // Get all go links for the current gene
          foreach($_SESSION["uploadedGenes".$_SESSION['namespace']] as $key => $gene){
            // Escape these genes with special chars to be used by grep commands
            $escgene = $_SESSION["uploadedGenesSys"][$key];
            $escgene = str_replace("(", "\\(", $escgene);
            $escgene = str_replace(")", "\\)", $escgene);
            $escgene = str_replace(".", "\\.", $escgene);
            $escgene = str_replace("-", "\\-", $escgene);
            $goList = shell_exec('egrep "'.$escgene.'" data/properties/go.tab');
            $emptyList = false;
            if($goList == ""){
              $emptyList = true;
            }else{
              $arrayItems = explode("\n",str_replace(array("\r\n","\n\r","\r"),"\n",$goList));
              $goListLinks = "";
              foreach($arrayItems as $item){
                // Make a list of go links that are hyperlinked
                preg_match("/\t(.*)/", $item, $match);
                if(isset($match[1])){
                  $goListLinks = $goListLinks.'<a href="http://www.ebi.ac.uk/QuickGO/GTerm?id='.$match[1].'">'.$match[1].'</a>, ';
                }else{
                  continue;
                }
              }
            }
            // Escape these genes with special chars to be used by grep commands
            $genesafe = $gene;
            $genesafe = str_replace("(", "ww", $genesafe);
            $genesafe = str_replace(")", "xx", $genesafe);
            $genesafe = str_replace(".", "yy", $genesafe);
            $genesafe = str_replace("-", "zz", $genesafe);
            echo '<div class="accordion-heading">';
            echo '<a class="accordion-toggle" data-toggle="collapse" href="#accordion'.$genesafe.'">'.$gene.'</a></div>';
            echo '<div id="accordion'.$genesafe.'" class="accordion-body collapse">';
            echo '<div class="well well-small">';
            if($emptyList == true)
              echo '<div class="accordion-toggle">No GO links were found...</div>';
            else
              echo '<div class="accordion-toggle">'.$goListLinks.'</div>';
            echo '</div></div>';
          }
        }
      ?>
      </div>
    </div>
  
  <?php include 'common/footer.php' ?>
  </body>
</html>
