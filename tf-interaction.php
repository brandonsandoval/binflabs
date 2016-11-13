<?php include("common/start.php"); custom_start(); ?>

<html lang="en">
  <?php 
    include 'common/header.php';
    // Only import scripts if user has uploaded genes
    if (isset($_SESSION["uploadedGenes"])) include 'interactions/graph-scripts.html';
    include 'common/navbar.php';
    include 'interactions/graph-styles.html';
  ?>

  <body>
    <div class="container">
      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGenes"])){
          echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of genes before continuing..</b></p></div>';
        
        } else {
          if ($_SESSION["namespace"] == 'ID') $field = 1;
          if ($_SESSION["namespace"] == 'Std') $field = 3;
          if (isset($_GET['graph_style'])) $graph_style = $_GET['graph_style'];
          else $graph_style = "Random";
          
          // Find the relevant lines in the tfbinds.tab file
          $relevant_interactions = array();
          $contents = "";
          foreach ($_SESSION["uploadedGenesSys"] as $gene) {
            $gene = preg_replace('/[(]/', '\(', $gene);
            $gene = preg_replace('/[)]/', '\)', $gene);
            $gene = preg_replace('/[.]/', '\.', $gene);
            $relevant_interactions = array_filter(array_unique(array_merge($relevant_interactions, explode("\n", trim(shell_exec("egrep \"$gene\\s|$gene$\" data/interactions/tfbinds.tab"))))));
            $contents .= shell_exec("egrep \"$gene\\s|$gene$\" data/interactions/tfbinds.tab");
          }
          file_put_contents('interactions/tf-interactions.tab', $contents);

          if (empty($relevant_interactions)) {
            include 'interactions/tf-interaction-error-view.html';
          } else {
            // Get an array of the unique TFs and another array of the unique genes
            $array_of_genes = array();
            $array_of_tfs = array();
            foreach ($relevant_interactions as $interaction) {
              preg_match('/^([A-Za-z0-9-]+?)\s+([A-Za-z0-9-]+?)$/', trim($interaction), $match);
              if (!in_array($match[1], $array_of_tfs)) array_push($array_of_tfs, $match[1]);
              if (!in_array($match[2], $array_of_genes)) array_push($array_of_genes, $match[2]);
            }
            $array_of_genes = array_diff($array_of_genes, $array_of_tfs);

            // Calculate the data for the graph
            $angle_differece = 2*pi()/(count($array_of_genes)+count($array_of_tfs));   //Used to calculate the positions of the nodes
            $count = 1;
            $file_contents = '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="directed" mode="static"><nodes>' . "\n";
            
            // Create nodes for TFs - convert them to the correct namespace
            foreach ($array_of_tfs as $tf) {
              $namespace_tf = $tf;
              if ($_SESSION["namespace"] != "Sys") {
                if(php_uname('s') == "Linux")
                  $namespace_tf = trim(shell_exec("grep -P \"\t$tf\t\" data/orf2std.tab | cut -f" . $field));
                else
                  $namespace_tf = trim(shell_exec("grep \"\t$tf\t\" data/orf2std.tab | cut -f" . $field));
              }
              if (empty($namespace_tf)) $namespace_tf = $tf;
              if ($graph_style == "Random") {
                if (in_array($tf, $_SESSION["uploadedGenesSys"])) $file_contents .= '<node id="' . $namespace_tf . '" label="' . $namespace_tf . '"><viz:size value="60"></viz:size><viz:color b="255" g="51" r="51"/><viz:position x="' . rand(0,2800) . '" y="' . rand(0,1000) . '"></viz:position></node>' . "\n";
                else $file_contents .= '<node id="' . $namespace_tf . '" label="' . $namespace_tf . '"><viz:size value="50"></viz:size><viz:color b="102" g="224" r="255"/><viz:position x="' . rand(0,2800) . '" y="' . rand(0,1000) . '"></viz:position></node>' . "\n";
              } else {
                if (in_array($tf, $_SESSION["uploadedGenesSys"])) $file_contents .= '<node id="' . $namespace_tf . '" label="' . $namespace_tf . '"><viz:size value="60"></viz:size><viz:color b="255" g="51" r="51"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
                else $file_contents .= '<node id="' . $namespace_tf . '" label="' . $namespace_tf . '"><viz:size value="50"></viz:size><viz:color b="102" g="224" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
                $count++;
              }
            }
            
            // Create nodes for genes - convert them to the correct namespace
            foreach ($array_of_genes as $gene) {
              $namespace_gene = $gene;
              if ($_SESSION["namespace"] != "Sys") {
                if(php_uname('s') == "Linux")
                  $namespace_gene = trim(shell_exec("grep -P \"\t$gene\t\" data/orf2std.tab | cut -f" . $field));
                else
                  $namespace_gene = trim(shell_exec("grep \"\t$gene\t\" data/orf2std.tab | cut -f" . $field));
              }
              if (empty($namespace_gene)) $namespace_gene = $gene;
              if ($graph_style == "Random") {
                if (in_array($gene, $_SESSION["uploadedGenesSys"])) $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="60"></viz:size><viz:color b="26" g="26" r="255"/><viz:position x="' . rand(0,2800) . '" y="' . rand(0,1000) . '"></viz:position></node>' . "\n";
                else $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="50"></viz:size><viz:color b="130" g="179" r="39"/><viz:position x="' . rand(0,2800) . '" y="' . rand(0,1000) . '"></viz:position></node>' . "\n";
              } else {
                if (in_array($gene, $_SESSION["uploadedGenesSys"])) $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="60"></viz:size><viz:color b="26" g="26" r="255"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
                else $file_contents .= '<node id="' . $namespace_gene . '" label="' . $namespace_gene . '"><viz:size value="50"></viz:size><viz:color b="130" g="179" r="39"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>' . "\n";
                $count++;
              }
            }
            $file_contents .= '</nodes><edges>' . "\n";

            // Create edges - convert them to the correct namespace
            foreach ($relevant_interactions as $interaction) {
              preg_match('/^([A-Za-z0-9-]+?)\s+([A-Za-z0-9-]+?)$/', trim($interaction), $match);
              $source = $match[1];
              $target = $match[2];
              $namespace_source = $source;
              $namespace_target = $target;
              if ($_SESSION["namespace"] != "Sys") {
                if(php_uname('s') == "Linux"){
                  $namespace_source = trim(shell_exec("grep -P \"\t$source\t\" data/orf2std.tab | cut -f" . $field));
                  $namespace_target = trim(shell_exec("grep -P \"\t$target\t\" data/orf2std.tab | cut -f" . $field));
                }else{
                  $namespace_source = trim(shell_exec("grep \"\t$source\t\" data/orf2std.tab | cut -f" . $field));
                  $namespace_target = trim(shell_exec("grep \"\t$target\t\" data/orf2std.tab | cut -f" . $field));
                }
              }
              if (empty($namespace_source)) $namespace_source = $source;
              if (empty($namespace_target)) $namespace_target = $target;
              $file_contents .= '<edge source="' . $namespace_source . '" target="' . $namespace_target . '"></edge>' . "\n";
            }

            $file_contents .= '</edges></graph></gexf>';
            shell_exec("rm 'js/sigma.js-1.2.0/data/tf-interactions.gexf'");
            file_put_contents('js/sigma.js-1.2.0/data/tf-interactions.gexf', $file_contents);
            
            include 'interactions/tf-interaction-scripts.js';
            include 'interactions/tf-interaction-view.html';
          }
        }
      ?>
  </div>
  <?php include 'common/footer.php' ?>
  </body>
</html>
