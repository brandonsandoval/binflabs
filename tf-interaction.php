<?php include("common/start.php"); custom_start(); ?>

<html lang="en">
  <?php 
    include 'common/header.php';
    // Only import scripts if user has uploaded genes
    if (isset($_SESSION["uploadedGenes"])) include 'interactions/graph-scripts.html';
    include 'common/navbar.php';
    include 'interactions/tf-interaction-scripts.js';
    include 'interactions/graph-styles.html';
  ?>

  <body>
    <div class="container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of genes before continuing..</b></p></div>';
        } else {
        // continue on if they have uploaded
          $relevant_interactions = array();
          foreach ($_SESSION["uploadedGenes"] as $gene) {
            $relevant_interactions = array_filter(array_unique(array_merge($relevant_interactions, explode("\n", trim(shell_exec("grep \"$gene\" data/interactions/tfbinds.tab"))))));
          }

          if (empty($relevant_interactions)) {
            include 'interactions/tf-interaction-error-view.html';
          } else {
            // Get an array of the unique TFs and another array of the unique genes
            $string_of_tfs = " ";
            $array_of_genes = array();
            $array_of_tfs = array();
            foreach ($relevant_interactions as $interaction) {
              $interaction_string = preg_replace('/\s+/', ' ', trim($interaction));
              $tf = trim(strstr($interaction_string, ' ', true));
              $gene = trim(strstr($interaction_string, ' '));
              if (!in_array($tf, $array_of_tfs)) array_push($array_of_tfs, $tf);
              if ((!in_array($gene, $array_of_tfs)) and (!in_array($gene, $array_of_genes))) array_push($array_of_genes, $gene);
            }

            // Calculate the data for the graph
            $angle_differece = 2*pi()/(count($array_of_genes)+count($array_of_tfs));   //Used to calculate the positions of the nodes
            $count = 1;
            $file_contents = '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="directed" mode="static"><nodes>';
            foreach ($array_of_tfs as $tf) {
              if (in_array($tf, $_SESSION["uploadedGenes"])) $file_contents .= '<node id="' . $tf . '" label="' . $tf . '"><viz:size value="100"></viz:size><viz:color b="251" g="51" r="51"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              else $file_contents .= '<node id="' . $tf . '" label="' . $tf . '"><viz:size value="100"></viz:size><viz:color b="102" g="224" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              $count++;
            }
            foreach ($array_of_genes as $gene) {
              if (in_array($gene, $_SESSION["uploadedGenes"])) $file_contents .= '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="100"></viz:size><viz:color b="26" g="26" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              else $file_contents .= '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="100"></viz:size><viz:color b="130" g="179" r="39"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
              $count++;
            }
            $file_contents .= '</nodes><edges>';
            foreach ($relevant_interactions as $interaction) {
              $interaction = preg_replace('/\s+/', ' ', $interaction);
              if (strstr($interaction, ' ', true) ==  trim(strstr($interaction, ' '))) $file_contents .= '<edge source="' . strstr($interaction, ' ', true) . '" target="' . trim(strstr($interaction, ' ')) . '"></edge>';
              else $file_contents .= '<edge source="' . strstr($interaction, ' ', true) . '" target="' . trim(strstr($interaction, ' ')) . '"></edge>';
            }
            $file_contents .= '</edges></graph></gexf>';
            file_put_contents('js/sigma.js-1.2.0/data/tf-interactions.gexf', $file_contents);
            
            include 'interactions/tf-interaction-view.html';
          }
        }
      ?>
  </div>
  <?php include 'common/footer.php' ?>
  </body>
</html>
