<?php session_start();
	// var_dump($_SESSION["channelInfo"]);
?>
<div class="content row">
	<div sidebar-nav active="usertags" profile-name='' col="2"></div>
	<div class="col-md-9 pull-left">  
	    <div id="beats" class="container no-padding" ng-show="toptags">	    	
			<div class="container box-rounded no-padding no-margin has-padding-vertical" ng-init="show='usertags'">
				<div class="container boxed-bottom has-padding-sm">
					<h4 class="pull-left" ng-show="show=='usertags'">User tags</h4>
					<h4 class="pull-left" ng-show="show=='toptags'">Top tags</h4>
					<div class="pull-right" ng-init="view='th'">
						<button type="button" class="btn btn-default" ng-click="view='th'">
						    <span class="glyphicon glyphicon-th"></span>
						</button>
						<button type="button" class="btn btn-default" ng-click="view='list'">
						    <span class="glyphicon glyphicon-th-list"></span>
						</button>
						<div class="btn-group">
						  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						    <span class="glyphicon glyphicon-wrench"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#" ng-click="show='usertags'">User tags</a></li>
						    <li><a href="#" ng-click="show='toptags'">Most used</a></li>
						  </ul>
						</div>
					</div>
				</div>							
				<div class="has-padding-sm" ng-class="{th:'pull-left'}[view]" ng-show="show=='usertags'" ng-repeat="tags in usertags | orderBy:'dateModified':true" ng-init="video.videoInfo = video.videoInfo.videoInfo">				
					<button type="button" class="btn btn-default no-border-radius has-margin-1">					   
					    {{tags.tagName}}
					</button>
					x{{tags.numberOfTimesUsed}}
				</div>
				<div class="has-padding-sm pull-left" ng-class="{th:'pull-left'}[view]" ng-show="show=='toptags'" ng-repeat="tags in toptags | orderBy:'dateModified':true" ng-init="video.videoInfo = video.videoInfo.videoInfo">
					<button type="button" class="btn btn-default no-border-radius has-margin-1">					   
					    {{tags.tagName}}
					</button>
					x{{tags.numberOfTimesUsed}}
				</div>
			</div>
		</div>		
	</div>
</div>
