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
          if ($_SESSION["namespace"] == 'ID') $search_regex = '^S[0-9]+';
          if ($_SESSION["namespace"] == 'Std') $search_regex = '[A-Za-z0-9_-]+$';

          // Find the relevant lines in the ppi.tab file
          $relevant_interactions = array();
          foreach ($_SESSION["uploadedGenesSys"] as $gene) {
            $relevant_interactions = array_filter(array_unique(array_merge($relevant_interactions, explode("\n", trim(shell_exec("grep \"$gene\\t\" data/interactions/ppi.tab"))))));
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
            $file_contents = '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="undirected" mode="static"><nodes>' . "\n";

            // Create nodes for genes - convert them to the correct namespace
            foreach ($array_of_genes as $gene) {
              $namespace_gene = $gene;
              if ($_SESSION["namespace"] != "Sys") {
                $namespace_gene = trim(shell_exec("grep \"$gene\" data/orf2std.tab | egrep -o \"$search_regex\""));
              }
              if (empty($namespace_gene)) $namespace_gene = $gene;
              if (in_array($gene, $_SESSION["uploadedGenesSys"])) $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="100"></viz:size><viz:color b="251" g="51" r="51"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
              else $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="100"></viz:size><viz:color b="102" g="224" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
              $count++;
            }
            $file_contents .= '</nodes><edges>' . "\n";

            // Create edges - convert them to the correct namespace
            foreach ($relevant_interactions as $interaction) {
              $interaction = preg_replace('/\s+/', ' ', $interaction);
              $source = strstr($interaction, ' ', true);
              $target = trim(strstr($interaction, ' '));
              $namespace_source = $source;
              $namespace_target = $target;
              if ($_SESSION["namespace"] != "Sys") {
                $namespace_source = trim(shell_exec("grep \"$source\" data/orf2std.tab | egrep -o \"$search_regex\""));
                $namespace_target = trim(shell_exec("grep \"$target\" data/orf2std.tab | egrep -o \"$search_regex\""));
              }
              if (empty($namespace_source)) $namespace_source = $source;
              if (empty($namespace_target)) $namespace_target = $target;
              $file_contents .= '<edge source="' . $namespace_source . '" target="' . $namespace_target . '"></edge>' . "\n";
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
