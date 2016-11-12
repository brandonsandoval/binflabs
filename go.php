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
          foreach($_SESSION["uploadedGenes".$_SESSION['namespace']] as $key => $gene){
            // Get all go links for the current gene
            $goList = shell_exec('egrep "'.$_SESSION["uploadedGenesSys"][$key].'" data/properties/go.tab');
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
            echo '<div class="accordion-heading">';
            echo '<a class="accordion-toggle" data-toggle="collapse" href="#accordion'.$gene.'">'.$gene.'</a></div>';
            echo '<div id="accordion'.$gene.'" class="accordion-body collapse">';
            echo '<div class="well well-small">';
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
