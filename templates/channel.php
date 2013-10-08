<?php session_start();
	// var_dump($_SESSION["channelInfo"]);
?>
<div class="content row">
	<div sidebar-nav active="userchannel" profile-name='' col="2"></div>
	<div class="col-md-9 pull-left">
		<div ng-hide="channelVideos.length>-1" class="center header"><img src="images/loading.gif" width=""></img></div>
		
		<div ng-show="channelVideos.length>-1" style="background-image:url('../uploads/channelBanner/guitar-header.jpg') !important;" class="jumbotron no-margin no-border-radius white-text hb-background whiteHover channel-banner">
	       <!--  <h3>My Channel!</h3> -->
	    </div>	  
	    <div id="beats" class="container no-padding" ng-show="activeRow.videoInfo">
	    	<div id="headbeat" ng-show="channelVideos.length>0" class="boxed-bottom boxed-left boxed-right no-padding no-margin has-padding-vertical">
			    <h4 class="container">Featured Video</h4>
			    <div class="container">
				    <div id="videoNode" class="col-md-7 no-padding-left">
						<iframe width="100%" height="100%" src="http://www.youtube.com/embed/{{activeRow.videoInfo.id}}?autoplay=1&modestbranding=1&rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>
					</div>					
					<div id="listNode" class="col-md-5">
						<div class="row">
							<div>								
								<h4><a title="{{activeRow.videoInfo.snippet.title}}" href="#!/play/{{activeRow.videoInfo.id}}">{{activeRow.videoInfo.snippet.title}}</a></h4>							</div>
							<small>
								<ul class="metaInfo">
									<li>by: {{activeRow.videoId}} <a href="{{ytLink_user}}{{activeRow.videoInfo.snippet.channelTitle}}" target="_blank" class="linkSmallBlack">{{activeRow.videoInfo.snippet.channelTitle}}</a></li>
									<li>&bull; <span ng-class="{snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{activeRow.videoInfo.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
									<li>&bull; <span ng-class="{statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{activeRow.videoInfo.statistics.viewCount | number}} Views</span></li>										
								</ul>
								<p>
									<span ng-hide="showMore" class="hideOverflow descShort">{{activeRow.videoInfo.snippet.description}}</span>
									<pre class="cleanPre" ng-show="showMore">{{activeRow.videoInfo.snippet.description}}</pre>
									<a href="" ng-click="showMoreFunc()">{{showMoreText}}</a>												
								</p>
							</small>
						</div>
					</div>
				</div>
			</div>
			<div ng-show="channelVideos.length>0" class="container boxed-bottom boxed-left boxed-right no-padding no-margin has-padding-vertical">
				<h4 class="container">My Videos</h4>
				<div class="container channel-video-inline pull-left" ng-repeat="video in filteredVideo = (channelVideos | orderBy:'videoInfo.statistics.viewCount':true | limitTo:4)" ng-class="{'active':'video.videoInfo.id==activeVideo.videoInfo.id'}">
					<a href="#!/play/{{video.videoInfo.id}}" title="Click thumbnail to play video"><img ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="180"></img></a>
					<div class="ellipsis">
						<div>
							<p><a title="{{video.videoInfo.snippet.title}}" href="#!/play/{{video.videoInfo.id}}">{{video.videoInfo.snippet.title}}</a></p>	
						</div>
					</div>
					<small>
						<ul class="metaInfo">
							<li><span>{{timeago(video.videoInfo.snippet.publishedAt)}} </span></li>
							<li>&bull; <span>{{video.videoInfo.statistics.viewCount | number}} Views</span></li>
						</ul>
						<!-- <p><span class="hideOverflow descShort">{{video.videoInfo.snippet.description}}</span></p> -->
					</small>
				</div>
			</div>
			<div ng-show="channelVideos.length<1" class="has-padding-sm boxed-bottom boxed-left boxed-right has-padding-vertical">
				<a class="btn btn-lg btn-primary" href="">You don't have video yet on your channel.</a>
			</div>	
			<!-- <pre>{{rankedVideos|json}}</pre> -->
			<div class="container boxed-bottom boxed-left boxed-right no-padding no-margin has-padding-vertical">				
				<h4 class="container boxed-bottom">Recently Ranked Video</h4>
				<div class="container no-padding-left" ng-repeat="video in rankedVideos | orderBy:'tagDateModified':true">
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
							
							<!-- <small ng-hide="currentSort.sorttext==''">
								<p>
									<span ng-class="{videoInfo.statistics.likeCount:'label label-warning'}[currentSort.sorttext]">Likes: {{video.videoInfo.statistics.likeCount | number}}</span> | 
									<span ng-class="{videoInfo.statistics.dislikeCount:'label label-warning'}[currentSort.sorttext]">Dislikes: {{video.videoInfo.statistics.dislikeCount | number}}</span> | 
									<span ng-class="{videoInfo.statistics.favoriteCount:'label label-warning'}[currentSort.sorttext]">Favorites: {{video.videoInfo.statistics.favoriteCount | number}}</span> | 
									<span ng-class="{videoInfo.statistics.commentCount:'label label-warning'}[currentSort.sorttext]">Comments: {{video.videoInfo.statistics.commentCount | number}}</span>						
								</p>
							</small>	 -->					
						</div>
						<small class="col-md-3"></small>
						<small class="col-md-8">
							<p><a href="#!/play/{{video.videoId}}">Edit Tags</a> <span class="bull">&bull;</span> {{timeago(video.tagDateModified)}}</p>
						</small>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
