<?php

    session_start();
    
    ini_set('display_errors', 'on');
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    
    define('IN_INDEX', 1);
    //shows erros

    date_default_timezone_set("America/Vancouver"); 
    
    //error_reporting(E_ALL ^ E_NOTICE);
    //includes the basic configuration
    include('includes/config.php');
    include('includes/ajax.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.ico">

    <title>Ping</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar-fixed-top.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <style>
    #map-canvas {
	width: 100%;
	height: 200px;
    }
    </style>
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Ping</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
	    <?php
		if (userLoggedIn()) {
	    ?>
	    <li class="dropdown">
		<a href="index.php?module=groups" class="dropdown-toggle" data-toggle="dropdown">Groups <b class="caret"></b></a>
		<ul class="dropdown-menu">
		    <li><a href="index.php?module=groups&action=mygroups">My Groups</a></li>
		    <li class="divider"></li>
		    <li><a href="index.php?module=groups&action=new">New Group</a></li>
		</ul>
	    </li>
	    <?php
		}
	    ?>
	    <!--
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
	    -->
          </ul>
	  
          <ul class="nav navbar-nav navbar-right">
		<?php
		  if (userLoggedIn()) {
		      echo '<li><a href="index.php?module=login">'.$userLogin->getName().'</a></li><li><a href="index.php?module=login&action=logout">Logout</a></li>';
		  } else {
		      echo '<li><a href="index.php?module=login">Login</a></li>';
		  }
		?>
            <!--<li><a href="../navbar-static-top/">Static top</a></li>
            <li class="active"><a href="./">Fixed top</a></li>-->
          </ul>
	  
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div class="row">
	<div class="md-col-12">
	    <div id="message" class="container">
		
	    </div>
	</div>
    </div>

    <div class="container">

	<?php
	    require('includes/modules.php');
	?>

    </div> <!-- /container -->
    <div id="scpt">
	
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
