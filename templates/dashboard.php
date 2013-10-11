<?php session_start();
	 // echo "<pre>".print_r($_SESSION,true)."</pre>";
?>
<div sidebar-nav active="newsfeed" col="2"></div>
<div class="col-md-7 pull-left">
	<!-- <pre>{{rankedVideos | json}}</pre> -->
	<!-- <pre>{{videoList | json}}</pre> -->
	<div class="boxed">
		<div class="container no-padding-left" ng-repeat="video in rankedVideos | orderBy:'tagDateModified':true">
			<div ng-show="video.videoInfo" class="container col-md-12 has-padding-vertical boxed-bottom">				
				<div class="col-md-12 no-padding no-margin update-feed-div">
					<div class="col-md-1 no-padding no-margin">
						<a class="pull-left" title="Play/Rate Video!" href="#!/play/{{video.videoId}}" ng-switch="video.taggerInfo.userinfo.id==null||video.taggerInfo.userinfo.picture==null">
							<img class="img-responsive" ng-src="images/profile-default-lg.svg" width="50" ng-switch-when="true"></img>
							<img class="img-responsive" ng-src="{{video.taggerInfo.userinfo.picture}}?sz=50"  ng-switch-when="false"></img>
						</a>
					</div>
					<div class="col-md-11 no-padding">
						<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.taggerInfo.authname}} </a><span class="inline text-ranked"> ranked</span></strong>
						<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></strong>
					</div>
				</div>
				<div class="has-margin-left-60">
					<div class="col-md-3 no-padding no-margin medium-height">
						<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="img-responsive" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="180"></img></a>
					</div>
					<div class="col-md-9 medium-height">
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
						<p>					
							<span class="bull">&bull;</span> {{timeago(video.tagDateModified)}}
						</p>
					</small>
				</div>
			</div>		
		</div>
			<!-- <pre>{{rankedVideos | json}}</pre> -->
	</div>
</div>

