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
        sigma.parsers.gexf('js/sigma.js-1.2.0/data/pp-interactions.gexf', {
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