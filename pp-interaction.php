<?php include("common/start.php"); custom_start(); ?>

<html lang="en">
  <?php 
    include 'common/header.php';
    // Only import scripts if user has uploaded genes
    if (isset($_SESSION["uploadedGenes"])) include 'interactions/graph-scripts.html';
    include 'common/navbar.php';
    include 'interactions/pp-interaction-scripts.js';
    include 'interactions/graph-styles.html';
  ?>

  <body>
    <div class="container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of genes before continuing..</b></p></div>';
        } else {
          // Find the relevant lines in the ppi.tab file
          $relevant_interactions = array();
          foreach ($_SESSION["uploadedGenes"] as $gene) {
            $relevant_interactions = array_filter(array_unique(array_merge($relevant_interactions, explode("\n", trim(shell_exec("grep \"$gene\" data/interactions/ppi.tab"))))));
          }

          if (empty($relevant_interactions)) {
            include 'interactions/pp-interaction-error-view.html';
          } else {
            // Get an array of the unique genes
            $string_of_genes = "";
            foreach ($relevant_interactions as $interaction) {
              $string_of_genes .= preg_replace('/\s+/', ' ', $interaction) . ' ';
            }
            $array_of_genes = explode("\n", trim(shell_exec("echo $string_of_genes | tr ' ' '\n' | sort | uniq")));
            
            // Create the data for the graph
            $angle_differece = 2*pi()/count($array_of_genes);   //Used to calculate the positions of the nodes
            $count = 1;
            $file_contents = '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="undirected" mode="static"><nodes>';
            foreach ($array_of_genes as $gene) {
              if (in_array($gene, $_SESSION["uploadedGenes"])) $file_contents .= '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="100"></viz:size><viz:color b="26" g="26" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              else $file_contents .= '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="100"></viz:size><viz:color b="130" g="179" r="39"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              $count++;
            }
            $file_contents .= '</nodes><edges>';
            foreach ($relevant_interactions as $interaction) {
              $interaction = preg_replace('/\s+/', ' ', $interaction);
              $file_contents .= '<edge source="' . strstr($interaction, ' ', true) . '" target="' . trim(strstr($interaction, ' ')) . '"></edge>';
            }
            $file_contents .= '</edges></graph></gexf>';
            file_put_contents('js/sigma.js-1.2.0/data/pp-interactions.gexf', $file_contents);
            
            include 'interactions/pp-interaction-view.html';
          }
        }
      ?>
    </div>
  <?php include 'common/footer.php' ?>
  </body>
</html>
