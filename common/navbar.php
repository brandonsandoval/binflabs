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
        if(basename($_SERVER['PHP_SELF']) == 'properties.php') echo " class=\"active\"";
      ?>>
      <a href="properties.php">Properties</a></li>
      
      <li<?php
        if(basename($_SERVER['PHP_SELF']) == 'go.php') echo " class=\"active\"";
      ?>>
      <a href="go.php">Ontology</a></li>
      
      <li class="dropdown <?php if(basename($_SERVER['PHP_SELF']) == 'expression.php') echo "active"; ?>">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Expression<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li class="dropdown-header">Absolute (causton)</li>
          <li><a href="expression.php?lab=causton&cond=acid">Acid</a></li>
          <li><a href="expression.php?lab=causton&cond=alkali">Alkali</a></li>
          <li><a href="expression.php?lab=causton&cond=h2o2">Peroxide</a></li>
          <li><a href="expression.php?lab=causton&cond=heat">Heat</a></li>
          <li><a href="expression.php?lab=causton&cond=salt">Salt</a></li>
          <li><a href="expression.php?lab=causton&cond=sorbitol">Sorbitol</a></li>
          <li role="separator" class="divider"></li>
          <li class="dropdown-header">Relative, log-odds (gasch)</li>
          <li><a href="expression.php?lab=gasch&cond=diamide">Diamide</a></li>
          <li><a href="expression.php?lab=gasch&cond=h2o2">Peroxide</a></li>
          <li><a href="expression.php?lab=gasch&cond=heat">Heat</a></li>
          <li><a href="expression.php?lab=gasch&cond=hyperosmotic">Sorbitol</a></li>
          <li><a href="expression.php?lab=gasch&cond=hypoosmotic">Hypo-osmotic</a></li>
          <li><a href="expression.php?lab=gasch&cond=menadione">Menadione</a></li>
        </ul>
      </li>
      
      <li class="dropdown <?php
        if(basename($_SERVER['PHP_SELF']) == 'pp-interaction.php' or basename($_SERVER['PHP_SELF']) == 'tf-interaction.php') echo ' active';
      ?>">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Interaction <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="pp-interaction.php">Protein-Protein Interactions</a></li>
          <li><a href="tf-interaction.php">Transcription Factor Binding</a></li>
        </ul>
      </li>
      
      <li<?php
        if(basename($_SERVER['PHP_SELF']) == 'about.php') echo " class=\"active\"";
      ?>>
      <a href="about.php">About</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Change Namespace <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="namespace.php?namespace=ID&return=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>">
            <?php
              if(!isset($_SESSION['namespace']))
                $_SESSION['namespace'] = "Sys";
              if($_SESSION['namespace'] == "ID") 
                echo '<span class="glyphicon glyphicon-check"></span>';
              else
                echo '<span class="glyphicon glyphicon-unchecked"></span>';
            ?>
            <b> SGD ID</b> (e.g. S000000001)</a></th>
          <li><a href="namespace.php?namespace=Sys&return=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>">
            <?php 
              if($_SESSION['namespace'] == "Sys") 
                echo '<span class="glyphicon glyphicon-check"></span>';
              else
                echo '<span class="glyphicon glyphicon-unchecked"></span>';
            ?>
            <b> Systematic Name</b> (e.g. YAL001C)</a></th>
          <li><a href="namespace.php?namespace=Std&return=<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>">
            <?php 
              if($_SESSION['namespace'] == "Std") 
                echo '<span class="glyphicon glyphicon-check"></span>';
              else
                echo '<span class="glyphicon glyphicon-unchecked"></span>';
            ?>
            <b> Standard Name</b> (e.g. TFC3)</a></th>
        </ul>
      </li>
    </ul>

  </div>
</div><!--/.nav-collapse -->
</nav>
