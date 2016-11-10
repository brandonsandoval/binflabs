<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="../binflabs">BinfLabs</a>
  </div>
  <div id="navbar" class="collapse navbar-collapse">
    <ul class="nav navbar-nav">
      <li<?php
        if(basename($_SERVER['PHP_SELF']) == 'index.php') echo " class=\"active\"";
      ?>>
      <a href="../binflabs">Upload</a></li>
      <li<?php
        if(basename($_SERVER['PHP_SELF']) == 'expression.php') echo " class=\"active\"";
      ?>>
      <a href="expression.php">Expression</a></li>
      
      <li class="dropdown"<?php
        if(basename($_SERVER['PHP_SELF']) == 'pp-interaction.php' or basename($_SERVER['PHP_SELF']) == 'tf-interaction.php') echo " class=\"active\"";
      ?>>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Interaction <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="pp-interaction.php">Protein-Protein Interactions</a></li>
          <li><a href="tf-interaction.php">Transcription Factor Binding</a></li>
        </ul>
      </li>
      <li
      <li<?php
        if(basename($_SERVER['PHP_SELF']) == 'about.php') echo " class=\"active\"";
      ?>>
      <a href="about.php">About</a></li>
    </ul>

  </div>
  </div><!--/.nav-collapse -->
</div>
</nav>
