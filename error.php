
<?php
	// session_start();	
	// if(session_id() == '') {
		// session_start();
		// unset($_SESSION["state"]);
		// unset($_SESSION["token"]);
		// unset($_SESSION["userinfo"]);
		// unset($_SESSION["valid"]);
		// unset($_SESSION["userlevel"]);
	// }
	// else {
		// unset($_SESSION["state"]);
		// unset($_SESSION["token"]);
		// unset($_SESSION["userinfo"]);
		// unset($_SESSION["valid"]);
		// unset($_SESSION["userlevel"]);
	// }
	if(isset($_GET["invalid"])){
	}
	else if(!isset($_GET["invalid"])){
		header("location: index.php");
	}
	
?>

<!DOCTYPE html>
<html lang="en" ng-app="videoTracker">
  <head>
    <meta charset="utf-8">
	<title>XSplit Video Tracker</title>	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<!--link rel="stylesheet" href="css/main.css"-->
	<link rel="stylesheet" href="component/bootstrap/bootstrap.css"/>
	<link rel="icon" type="image/ico" href="favicon.ico">

    <!-- Le styles -->
    <link href="component/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">	
    <link href="component/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top greyHeader">
      <div class="navbar-inner">
        <div class="container-fluid greyHeader">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">XSplit Video Tracker</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              You're not currently logged in<a href="#" class="navbar-link"></a>
            </p>
            <ul class="nav" bs-navbar>
              <li class="active" data-match-route=""><a href="#">Login Page</a></li>
              <li data-match-route="/about"><a href="#about">About</a></li>
              <li data-match-route="/contact"><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	  <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h3>Stop!</h3>
        <p>You don't have access do this app.</p>
        <p><a href="login.php" class="btn btn-primary btn-large">Try another account &raquo;</a></p>
      </div>
      <hr>
      <footer>
        <p>&copy; any.TV Limited 2013</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->	
	<script src="component/jquery/jquery.js"></script>
    <script src="component/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
