<?php session_start();
	// var_dump($_SESSION["channelInfo"]);
?>
<div class="content row">
	<div sidebar-nav active="ranklater" profile-name='' col="2"></div>
	<div class="col-md-9 pull-left">  
	    <div id="beats" class="container no-padding" ng-show="ranklist">	    	
			<div class="container boxed no-padding no-margin has-padding-vertical">				
				<h4 class="container boxed-bottom has-padding-sm">Rank Later Videos</h4>
				<div ng-mouseover="video.hovered=true" ng-mouseleave="video.hovered=false" class="container no-padding-left" ng-repeat="video in ranklist | orderBy:'dateModified':true" ng-init="video.videoInfo = video.videoInfo.videoInfo">
					<!-- <pre>{{video|json}}</pre> -->
					<div ng-show="video.videoInfo" class="container col-md-9 has-padding-vertical boxed-bottom">
						<div class="col-md-3 no-padding no-margin medium-height">
							<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="img-responsive" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="180"></img></a>				
						</div>					
						<div class="col-md-9 medium-height">
							<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></strong>						
							<br/>
							<small>
								<ul class="metaInfo">
									<li>by: <a href="{{ytLinkChannel}}{{video.videoInfo.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{video.videoInfo.snippet.channelTitle}}</a></li>
									<li>&bull; <span ng-class="{videoInfo.snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
									<li>&bull; <span ng-class="{videoInfo.statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.statistics.viewCount | number}} Views</span></li>										
								</ul>								
								<!-- <p><span class="hideOverflow descShort">{{video.videoInfo.snippet.description}}</span></p> -->																							
								<div ng-show="video.groupedvideotags['primary'].length>0">
									<span class="inline">Primary:</span>
									<span class="no-padding-left" ng-repeat="tag in video.groupedvideotags['primary']">{{tag.prefix}} {{tag.name}}<span ng-show="video.groupedvideotags['primary'].length-1>$index">,</span></span>
								</div>
								<div ng-show="video.groupedvideotags['copyright'].length>0">
									<span class="inline">Copyright:</span>
									<span class="no-padding-left" ng-repeat="tag in video.groupedvideotags['copyright']">{{tag.name}} {{tag.prefix}}<span ng-show="video.groupedvideotags['copyright'].length-1>$index">,</span></span>
								</div>		
								<div ng-show="video.groupedvideotags['language'].length>0">
									<span class="inline">Language:</span>
									<span class="no-padding-left" ng-repeat="tag in video.groupedvideotags['language']">{{tag.prefix}} {{tag.name}}<span ng-show="video.groupedvideotags['language'].length-1>$index">,</span></span>
								</div>
								<div ng-show="video.groupedvideotags['tags'].length>0">
									<span class="inline">Tags:</span>
									<span class="no-padding-left" ng-repeat="tag in video.groupedvideotags['tags']">{{tag.prefix}} {{tag.name}}<span ng-show="video.groupedvideotags['tags'].length-1>$index">,</span></span>
								</div>																									
							</small>			
						</div>
						<small class="col-md-3"></small>
						<small class="col-md-8">
							<p><a href="#!/play/{{video.videoId}}" ng-class="{'color-grey':video.ranked}"> {{ranked(video.ranked)}}</a> <span class="bull">&bull;</span> {{timeago(video.dateModified)}}</p>
						</small>
						<!-- <span ng-show="video.hovered" class="glyphicon glyphicon-trash dropdown-toggle pointer-cursor pull-right" data-toggle="dropdown"></span> -->
					</div>
					<div ng-hide="video.videoInfo" class="container col-md-9 has-padding-vertical boxed-bottom">
						<h3>No video here yet!</h3>
						<!-- <span ng-show="video.hovered" class="glyphicon glyphicon-trash dropdown-toggle pointer-cursor pull-right" data-toggle="dropdown"></span> -->
					</div>

				<!-- <pre> 	q {{ranklist|json}}</pre>	 -->
					<span ng-show="video.hovered" class="glyphicon glyphicon-trash dropdown-toggle pointer-cursor pull-right" data-toggle="dropdown"></span>
					<!-- <div class="btn-group pull-right">
					  <span ng-show="video.hovered" class="glyphicon glyphicon-cog dropdown-toggle pointer-cursor" data-toggle="dropdown"></span>
					  <ul ng-show="video.hovered" class="dropdown-menu" role="menu">
					    <li><a href="#">Recent</a></li>
					    <li><a href="#">Trending</a></li>
					    <li><a href="#">Something else here</a></li>
					    <li class="divider"></li>
					    <li><a href="#">Remove</a></li>
					  </ul>
					</div> -->
				</div>		
			</div>						
		</div>		
	</div>
</div>
