<?php
	session_start();
	if(isset($_SESSION["valid"])) {		
		header("location: home.php");
	}
?>

<!DOCTYPE html>
<html lang="en" ng-app="videoTracker">
  <head>
    <meta charset="utf-8">
	<title>Heartbeat</title>	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<!--link rel="stylesheet" href="css/main.css"-->
	<!--link rel="stylesheet" href="component/bootstrap/bootstrap.css"/-->
	<!-- <link rel="icon" type="image/ico" href="favicon.ico"> -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="component/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="component/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="component/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="component/bootstrap/assets/ico/favicon.png">
    <!-- Le styles -->	
  <link href="component/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">	
    <!--link href="component/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"-->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  </head>

  <body>
    <div id="wrap">
          <div class="navbar navbar-fixed-top no-border no-padding">
            <nav class="navbar navbar-default no-margin no-border" role="navigation">    
              <div class="navbar-header">        
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>   
                <a class="navbar-brand less-padding-vertical" href="#"><img src="images/heartbeat-logo.png" height="22"></img></a>                    
               </div>
               <div class="collapse navbar-collapse navbar-ex1-collapse no-margin reduce-height">                         
                   <ul class="nav navbar-nav">
                      <li class="active" data-match-route=""><a href="#">Login Page</a></li>
                      <li data-match-route="/about"><a href="http://www.any.tv">any.TV</a></li>
                      <li data-match-route="/about"><a href="http://www.youtube.com">YouTube</a></li>
                  </ul>
                </div>             
            </nav>
          </div>
          <div class="container">
                <div class="jumbotron no-border-radius">
                   <h1>Hello, YouTubers!<a href="login.php" class="pull-right"><img src="images/googleBtn.png" width="400"></img></a></h1>
                   <p>Welcome to the Heartbeat.TM! </p>
                   <p>The new site for You, a Dating for YouTubers!</p>             
                </div>
                <div class="row">
                  <div class="col-6 col-sm-6 col-lg-4">
                    <h3><li class="icon-search"></li> Realtime YouTube Search</h3>
                    <p>Find your videos right here, right now! awesome real-time search straight from youtube's to this super-cool-awesome app greatly reduces waiting time for queues so you get what you search for instantly! well, most of the time anyways. </p>
                    <!-- <p><a class="btn btn-default" href="#">View details &raquo;</a></p> -->
                  </div><!--/span-->
                  <div class="col-6 col-sm-6 col-lg-4">
                   <h3><li class="icon-hdd"></li> Search your rated videos</h3>
                  <p>Search a video, rate it, comment on it and save it on the database! this tool helps save a ridiculous amount of time in indexing and sorting out videos. This allows for much more convenient searches, from youtube and from our database as well. but don't take my word for it, really, don't. no seriously. really. just try it out and let the magic come to you.</p>
                    <!-- <p><a class="btn btn-default" href="#">View details &raquo;</a></p> -->
                  </div><!--/span-->
                  <div class="col-6 col-sm-6 col-lg-4">
                     <h3><li class="icon-star"></li> Powered by AngularJS</h3>
                  <p>This tool is powered by the most super-heroic of all super-heroic framework. Angularjs provides this app with the realtime searches you need all the while maintaining ease of use and user friendliness. Thanks AngularJs! you're my hero!</p>
                    <!-- <p><a class="btn btn-default" href="#">View details &raquo;</a></p> -->
                  </div><!--/span-->            
                </div><!--/row-->
                <footer>
                  <p class="pull-right">any.TV Limited</P>
                </footer>  
          </div>  

    </div>
   

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->	
	<script src="component/jquery/jquery.js"></script>
    <script src="component/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
