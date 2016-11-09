<?php include("common/start.php"); custom_start();

  // Grab uploaded data if it was posted from index.php
  if(isset($_POST["submit"]) && ($_POST["submit"] == "Upload")){
    echo "<p>POST DATA:</p>";
    var_dump($_POST);
    // Store the list as an array in uploadGenes separated by newlines
    $_SESSION["uploadedGeneInteractions"] = explode("\n", str_replace(array("\r\n","\n\r","\r"),"\n",$_POST["genesTextbox"]));
  }
?>

<html lang="en">
  <?php include 'common/header.php' ?>
  <?php include 'common/navbar.php' ?>

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

<style>
  body {
    color: #333;
    font-size: 14px;
  }
  #graph-container {
    top: 20%;
    bottom: 20%;
    left: 30%;
    right: 30%;
    position: absolute;
    background-color: #f4f0e4;
  }
  #control-pane {
    top: 10px;
    /*bottom: 10px;*/
    right: 10px;
    width: 230px;
    background-color: rgb(249, 247, 237);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  }
  #control-pane > div {
    margin: 10px;
    overflow-x: auto;s
  }
  h2, h3, h4 {
    padding: 0;
    font-variant: small-caps;
  }
  h2.underline {
    color: #437356;
    background: #f4f0e4;
    margin: 0;
    border-radius: 2px;
    padding: 8px 12px;
    font-weight: 700;
  }
  .hidden {
    display: none;
    visibility: hidden;
  }

  input[type=range] {
    width: 160px;
  }

</style>

  <body>

      <?php
        // Display notice if user has not uploaded anything
        if(!isset($_SESSION["uploadedGeneInteractions"])){
          echo '<div class="inner-container"><p><b>Please <a href="index.php">upload</a> a list of gene interactions before continuing..</b></p></div>';
        } else {
        // continue on if they have uploaded
          // Get an array of the unique genes

          $string_of_genes = "";
          foreach ($_SESSION["uploadedGeneInteractions"] as $interaction) {
            $string_of_genes .= preg_replace('/\s+/', ' ', $interaction) . ' ';
          }
          $array_of_genes = explode("\n", trim(shell_exec("echo $string_of_genes | tr ' ' '\n' | sort | uniq")));
          
          file_put_contents('js/sigma.js-1.2.0/data/interactions.gexf', '<?xml version="1.0" encoding="UTF-8"?><gexf xmlns:viz="http://www.gexf.net/1.2draft/viz"><graph defaultedgetype="undirected" mode="static"><nodes>');
          foreach ($array_of_genes as $gene) {
            file_put_contents('js/sigma.js-1.2.0/data/interactions.gexf', file_get_contents('js/sigma.js-1.2.0/data/interactions.gexf') . '<node id="' . $gene . '" label="' . $gene . '"><viz:size value="150"></viz:size><viz:position x="' . rand(0,1000) . '" y="' . rand(0,1000) . '"></viz:position></node>');
          }
          file_put_contents('js/sigma.js-1.2.0/data/interactions.gexf', file_get_contents('js/sigma.js-1.2.0/data/interactions.gexf') . '</nodes><edges>');
          foreach ($_SESSION["uploadedGeneInteractions"] as $interaction) {
            $interaction = preg_replace('/\s+/', ' ', $interaction);
            file_put_contents('js/sigma.js-1.2.0/data/interactions.gexf', file_get_contents('js/sigma.js-1.2.0/data/interactions.gexf') . '<edge source="' . strstr($interaction, ' ', true) . '" target="' . trim(strstr($interaction, ' ')) . '"></edge>');
          }
          file_put_contents('js/sigma.js-1.2.0/data/interactions.gexf', file_get_contents('js/sigma.js-1.2.0/data/interactions.gexf') . '</edges></graph></gexf>');
      ?>

      <script>
      var filter;

      /**
       * DOM utility functions
       */
      var _ = {
        $: function (id) {
          return document.getElementById(id);
        },

        all: function (selectors) {
          return document.querySelectorAll(selectors);
        },

        removeClass: function(selectors, cssClass) {
          var nodes = document.querySelectorAll(selectors);
          var l = nodes.length;
          for ( i = 0 ; i < l; i++ ) {
            var el = nodes[i];
            // Bootstrap compatibility
            el.className = el.className.replace(cssClass, '');
          }
        },

        addClass: function (selectors, cssClass) {
          var nodes = document.querySelectorAll(selectors);
          var l = nodes.length;
          for ( i = 0 ; i < l; i++ ) {
            var el = nodes[i];
            // Bootstrap compatibility
            if (-1 == el.className.indexOf(cssClass)) {
              el.className += ' ' + cssClass;
            }
          }
        },

        show: function (selectors) {
          this.removeClass(selectors, 'hidden');
        },

        hide: function (selectors) {
          this.addClass(selectors, 'hidden');
        },

        toggle: function (selectors, cssClass) {
          var cssClass = cssClass || "hidden";
          var nodes = document.querySelectorAll(selectors);
          var l = nodes.length;
          for ( i = 0 ; i < l; i++ ) {
            var el = nodes[i];
            //el.style.display = (el.style.display != 'none' ? 'none' : '' );
            // Bootstrap compatibility
            if (-1 !== el.className.indexOf(cssClass)) {
              el.className = el.className.replace(cssClass, '');
            } else {
              el.className += ' ' + cssClass;
            }
          }
        }
      };


      function updatePane (graph, filter) {
        // get max degree
        var maxDegree = 0;
        
        // read nodes
        graph.nodes().forEach(function(n) {
          maxDegree = Math.max(maxDegree, graph.degree(n.id));
        })

        // min degree
        _.$('min-degree').max = maxDegree;
        _.$('max-degree-value').textContent = maxDegree;
      }

      // Initialize sigma with the dataset:
      sigma.parsers.gexf('js/sigma.js-1.2.0/data/interactions.gexf', {
        container: 'graph-container',
        settings: {
          edgeColor: 'default',
          defaultEdgeColor: '#ccc'
        }
      },

      function(s) {
        // Initialize the Filter API
        filter = new sigma.plugins.filter(s);

        updatePane(s.graph, filter);

        var dragListener = sigma.plugins.dragNodes(s, s.renderers[0]);

        dragListener.bind('startdrag', function(event) {
          console.log(event);
        });
        dragListener.bind('drag', function(event) {
          console.log(event);
        });
        dragListener.bind('drop', function(event) {
          console.log(event);
        });
        dragListener.bind('dragend', function(event) {
          console.log(event);
        });

        function applyMinDegreeFilter(e) {
          var v = e.target.value;
          _.$('min-degree-val').textContent = v;

          filter
            .undo('min-degree')
            .nodesBy(function(n) {
              return this.degree(n.id) >= v;
            }, 'min-degree')
            .apply();
        }

        _.$('min-degree').addEventListener("input", applyMinDegreeFilter);  // for Chrome and FF
        _.$('min-degree').addEventListener("change", applyMinDegreeFilter); // for IE10+
      });

      
      </script>

      <div id="container">
        <div id="graph-container"></div>
        <div id="control-pane">
          <h2 class="underline">filters</h2>
          <div>
            <h3>min degree: <span id="min-degree-val">0</span></h3>
            0 <input id="min-degree" type="range" min="0" max="0" value="0"> <span id="max-degree-value">0</span><br>
          </div>
          <span class="line"></span>
          <div id="dump" class="hidden"></div>
        </div>
      </div>


      <?php
        } // End of: if(!isset($_SESSION["uploadedGeneInteractions"]))
      ?>


  <?php include 'common/footer.php' ?>
  </body>
</html>
