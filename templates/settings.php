<?php
	session_start();
	if(isset($_SESSION["userlevel"])) {
		if($_SESSION["userlevel"]!='admin') {
			header("location: ../error.php");
		}		
	}
	else{
		header("location: index.php");
	}
?>
<div class="container" ng-controller="settingsController">
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas">
	  <div class="well sidebar-nav">
		<ul class="nav">		  
		  <li><a href="#!/settings/ratings" ng-click="">Change Ratings</a></li>
		  <li><a href="#!/settings/upload" ng-click="">Upload Video Data</a></li>
		  <li><a href="#!/admin" ng-click="">Admin</a></li>
		</ul>
	  </div>
	</div>
</div>