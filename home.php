<?php
session_start();
if(!(isset($_SESSION["valid"]))) {
		// header("location: error.php");
	$_SESSION["guest"]=true;
}
?>
<!DOCTYPE html>
<html lang="en" id="ng-app" ng-app="videoTracker">
<head>
	<meta charset="utf-8">
	<meta property="og:title" content="Heartbeat - Dating for YouTubers">
	<meta property="og:type" content="article">
	<meta property="og:url" content="http://www.heartbeat.tm">
	<meta property="og:image" content="http://www.heartbeat.tm/images/anytvlogo.png">
	<meta property="og:site_name" content="Heartbeat">
	<meta property="og:description" content="Heartbeat is a platform made by YouTubers for YouTubers, a Dating for YouTubers! Open your YouTube channel on Heartbeat and start ranking them!">
	<meta name="Description" content="Heartbeat is a platform made by YouTubers for YouTubers, a Dating for YouTubers! Open your YouTube channel on Heartbeat and start ranking them! any.TV, Believe in you.">
	<meta name="keywords" content="Heartbeat, AnyTV, any.TV, Rank Videos, YouTube, YouTubers, Tag Video, Heartbeat.tm, Channel, Videos, heartbeat, Dating, Dating for YouTubers, for YouTubers" />
	<title>Heartbeat</title>	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="any.TV Developers">
	<meta name="fragment" content="!"/>
	<script src="component/jquery/jquery.min.js"></script>
	<script src="component/chosen/chosen.jquery.js"></script>
	<script src="js/angular.min.js"></script>		
	<script src="js/ui-bootstrap-tpls-0.6.0.min.js"></script>
	
	<script src="js/defaultController.js"></script>
	<script src="js/angularUI.js"></script>
	<script src="js/app.js"></script>
	<script src="js/dashViewController.js"></script>
	<script src="js/controller.js"></script>
	<script src="js/dbViewController.js"></script>	
	<script src="js/playController.js"></script>
	<script src="js/channelController.js"></script>
	<script src="js/settingsController.js"></script>
	<script src="js/angular-strap.js"></script>
	<script src="js/angular-sanitize.js"></script>
	<script src="js/angular-resource.js"></script>	
	<script src="js/ng-upload.js"></script>
	<script src="js/ui-jq.js"></script>
	<script src="js/adminController.js"></script>
	<script src="js/groupController.js"></script>
	<script src="component/ngTagsInput/ng-tags-input.js"></script>
	<script src="component/select2/select2.min.js"></script>	


	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="component/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="component/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="component/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="component/bootstrap/assets/ico/favicon.png">

	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/sidemenu.css" rel="stylesheet">    
	<link href="component/chosen/chosen.css" rel="stylesheet">	
	<link href="component/ngTagsInput/ng-tags-input.css" rel="stylesheet">	
	<link href="component/select2/select2.css" rel="stylesheet">	
	
</head>
<body>
	<div id="container">
		<!-- <input type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo" data-role="tagsinput" /> -->
		<div class="page-wrap">
			<div id="header" class="navbar navbar-inverse navbar-fixed-top navbar-red">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand mainlogo-container" href="#"><img src="images/heartbeat-logo.png" height="22"></img></a>
					</div>
					<div class="navbar-collapse collapse" ng-controller="defaultController">
				        <form class="navbar-form navbar-left big-search-box" role="search" id="search" ng-submit="search(keyword)">
				        	<div class="form-group">
				        		<input type="text" id="keyword" delayed-search class="form-control input-sm no-border-radius main-search" ng-change="resetPage()" placeholder="Search Video" ng-model="keyword" callback-search="searchDelay(arg)">
				        	</div>
				        	<button type="submit" class="btn no-border-radius btn-sm btn-default">&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-search"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
				        	<!-- <div class="form-group dropdown"> -->
				        		<!-- <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="">
				        			Go to &nbsp;<span class="caret"></span>
				        		</a>	 -->					
				        		<!-- <ul class="dropdown-menu">
				        			<li><a href="//any.tv/games">Games</a></li>
				        			<li><a href="#!/rank">Videos</a></li>
				        			<li><a href="" onClick="doSwap()" ng-show="page=='play'">Swap View</a></li>
				        		</ul> -->
				        	<!-- </div> -->
				        	
				        </form>        
				        <ul class="nav navbar-nav navbar-right">				  
				        	<li class="dropdown">
				        		<a class="dropdown-toggle user-menu-dropdown" title="Click to view settings" id="dLabel" role="button" data-toggle="dropdown" data-target="" href="/page.html">
				        			<?php echo (isset($_SESSION["userinfo"]["name"])?$_SESSION["userinfo"]["name"]:""); if(isset($_SESSION["userlevel"])) if($_SESSION["userlevel"]!="guest") echo '('.$_SESSION["userlevel"].')'; ?>
				        			<img class="no-margin" height="30" src="<?php echo $_SESSION["thumbnail"]; ?>" alt="" class="img-thumbnail">
				        			<span class="caret"></span>
				        		</a>
				        		<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				        			<li class="">
				        				<a href="#!/mychannel"><i class="icon-search"></i> My Channel</a>
				        			</li>
				        			<li class="">
				        				<a href=""><i class="icon-user"></i> Beats</a>
				        			</li>
				        			<li class="<?php if(isset($_SESSION["userlevel"])) if($_SESSION["userlevel"]!='admin') echo 'disabled'; ?>">
				        				<a href="<?php if(isset($_SESSION["userlevel"])) if($_SESSION["userlevel"]=='admin') echo '#!/settings'; ?>"><i class="icon-cog"></i> Settings</a>
				        			</li>
				        			<li class="divider"></li>
				        			<li class="">
				        				<a href="logout.php"><i class="icon-remove"></i> Log-out</a>
				        			</li>
				        		</ul>
				        	</li>		
				        </ul>
		  		  </div>
				</div>
			</div>			
			<div ng-view></div>
			<!-- <div id="footer" ng-hide="page=='play'">
				<div class="container" style="text-align:center">			
					<br/><div><a href="#">Home</a> &nbsp <a href="//www.facebook.com/anyTVnetwork‎" target="_blank">Facebook</a> &nbsp <a href="//twitter.com/anyTVnetwork" target="_blank">Twitter</a> &nbsp <a href="//plus.google.com/109971475987405213729/videos" target="_blank">Google+</a> &nbsp <a href="//www.youtube.com/user/anyTVnetwork" target="_blank">YouTube</a><br/>
						<small style="text-align:center;"><a href="//www.any.tv">any.TV Limited</a> &copy 2013 | Believe in You!</small>
					</div>			
					<br/><div>
						<a href="http://www.any.tv" class="pull-right"> <img src="images/poweredby.png" class="img-responsive img-polaroid" width="100" height="100"></a>						   
					</div>
				</div>
			</div> -->
		</div>		
	</div>	

	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>	
    <script src="component/typeHead/typehead.js"></script>
    <script src="component/typeHead/igTypeahead.js"></script>
    <script src="component/timeago/timeago.js"></script>
    <script src="component/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script src="js/human.js"></script>
    <script type='text/javascript'>
		$(".alert").alert();
		$('#navbar').affix();
		$('#example').tooltip();
		$('#sideDetailView').affix();
		function doSwap() {
			swapElements(document.getElementById("videoNode"), document.getElementById("listNode"));
		}
		function swapElements(obj1, obj2) {
			obj2.nextSibling === obj1
			? obj1.parentNode.insertBefore(obj2, obj1.nextSibling)
			: obj1.parentNode.insertBefore(obj2, obj1);						
		}		
	</script>
  </body>  
</html>
