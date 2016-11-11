<?php include("common/start.php"); custom_start(); ?>

<html lang="en">
  <?php 
    include 'common/header.php';
    // Only import scripts if user has uploaded genes
    if(isset($_SESSION["uploadedGenes"])){
echo <<< EOT
      <script src="js/sigma.js-1.2.0/src/sigma.core.js"></script>
      <script src="js/sigma.js-1.2.0/src/conrad.js"></script>
      <script src="js/sigma.js-1.2.0/src/utils/sigma.utils.js"></script>
      <script src="js/sigma.js-1.2.0/src/utils/sigma.polyfills.js"></script>
      <script src="js/sigma.js-1.2.0/src/sigma.settings.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.dispatcher.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.configurable.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.graph.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.camera.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.quad.js"></script>
      <script src="js/sigma.js-1.2.0/src/classes/sigma.classes.edgequad.js"></script>
      <script src="js/sigma.js-1.2.0/src/captors/sigma.captors.mouse.js"></script>
      <script src="js/sigma.js-1.2.0/src/captors/sigma.captors.touch.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/sigma.renderers.canvas.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/sigma.renderers.webgl.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/sigma.renderers.svg.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/sigma.renderers.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/webgl/sigma.webgl.nodes.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/webgl/sigma.webgl.nodes.fast.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/webgl/sigma.webgl.edges.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/webgl/sigma.webgl.edges.fast.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/webgl/sigma.webgl.edges.arrow.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.labels.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.hovers.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.nodes.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edges.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edges.curve.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edges.arrow.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edges.curvedArrow.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edgehovers.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edgehovers.curve.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edgehovers.arrow.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.edgehovers.curvedArrow.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/canvas/sigma.canvas.extremities.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.utils.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.nodes.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.edges.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.edges.curve.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.labels.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/renderers/svg/sigma.svg.hovers.def.js"></script>
      <script src="js/sigma.js-1.2.0/src/middlewares/sigma.middlewares.rescale.js"></script>
      <script src="js/sigma.js-1.2.0/src/middlewares/sigma.middlewares.copy.js"></script>
      <script src="js/sigma.js-1.2.0/src/misc/sigma.misc.animation.js"></script>
      <script src="js/sigma.js-1.2.0/src/misc/sigma.misc.bindEvents.js"></script>
      <script src="js/sigma.js-1.2.0/src/misc/sigma.misc.bindDOMEvents.js"></script>
      <script src="js/sigma.js-1.2.0/src/misc/sigma.misc.drawHovers.js"></script>
      <script src="js/sigma.js-1.2.0/plugins/sigma.parsers.gexf/gexf-parser.js"></script>
      <script src="js/sigma.js-1.2.0/plugins/sigma.parsers.gexf/sigma.parsers.gexf.js"></script>
      <script src="js/sigma.js-1.2.0/plugins/sigma.plugins.filter/sigma.plugins.filter.js"></script>
      <script src="js/sigma.js-1.2.0/plugins/sigma.plugins.dragNodes/sigma.plugins.dragNodes.js"></script>
EOT;
    }
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
        // continue on if they have uploaded

          // Find the relevant lines in the ppi.tab file
          $relevant_interactions = [];
          foreach ($_SESSION["uploadedGenes"] as $gene) {
            $relevant_interactions = array_unique(array_merge($relevant_interactions, explode("\n", trim(shell_exec("grep \"$gene\" data/interactions/ppi.tab")))));
          }

          // Get an array of the unique genes
          $string_of_genes = "";
          foreach ($relevant_interactions as $interaction) {
            $string_of_genes .= preg_replace('/\s+/', ' ', $interaction) . ' ';
          }
          $array_of_genes = explode("\n", trim(shell_exec("echo $string_of_genes | tr ' ' '\n' | sort | uniq")));
          
          // Create the data for the graph
          $angle_differece = 2*pi()/count($array_of_genes);   //Userd to calculate the positions of thne nodes
          $count = 1;
          $file_contents = '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="undirected" mode="static"><nodes>';
          foreach ($array_of_genes as $gene) {
            $file_contents .= '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="100"></viz:size><viz:color b="130" g="179" r="39"/><viz:position x="' . 1000*sin(2*pi()-$angle_differece*$count) . '" y="' . 1000*cos(2*pi()-$angle_differece*$count) . '"></viz:position></node>';
            $count++;
          }
          $file_contents .= '</nodes><edges>';
          foreach ($relevant_interactions as $interaction) {
            $interaction = preg_replace('/\s+/', ' ', $interaction);
            $file_contents .= '<edge source="' . strstr($interaction, ' ', true) . '" target="' . trim(strstr($interaction, ' ')) . '"></edge>';
          }
          $file_contents .= '</edges></graph></gexf>';
          file_put_contents('js/sigma.js-1.2.0/data/pp-interactions.gexf', $file_contents);
      ?>

      <br>
      <div id="container-fluid">
        <div class="alert alert-info content" style="margin-left: 10px; margin-right: 10px;">
          <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
          The graph below shows <b>protein-protein interactions</b>. Proteins appear as nodes and interactions are depicted as undirected edges.
        </div>
        <div id="graph-container"></div>
        <div id="control-pane">
          <div>
            <div class="row" style="text-align: center;">
              <b>User the slider to adjust the minimum node degree.</b> (Current minimum degree: <span id="min-degree-val">0</span>)
            </div>
            <div class="row">
              <div class="col-md-1 col-md-offset-4">
                0
              </div>
              <div class="col-md-3">
                <input id="min-degree" type="range" min="0" max="0" value="0">
              </div>
              <div class="col-md-1">
                <span id="max-degree-value"></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
        } // End of: if(!isset($_SESSION["uploadedGenes"]))
      ?>
    </div>
  <?php include 'common/footer.php' ?>
  </body>
</html>
