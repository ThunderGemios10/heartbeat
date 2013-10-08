<?php session_start();
	 // echo "<pre>".print_r($_SESSION,true)."</pre>";
?>
<div sidebar-nav active="newsfeed" col="2"></div>
<div class="col-md-9 pull-left">
	<!-- <pre>{{rankedVideos | json}}</pre> -->
	<!-- <pre>{{videoList | json}}</pre> -->
	<div class="boxed">
		<div class="container no-padding-left" ng-repeat="video in rankedVideos | orderBy:'tagDateModified':true">
			<div ng-show="video.videoInfo" class="container col-md-9 has-padding-vertical boxed-bottom">
				<div class="col-md-3 no-padding no-margin medium-height">
					<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="img-responsive" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="180"></img></a>				
				</div>					
				<div class="col-md-9 medium-height">					
					<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}} - {{video.taggerEmail}}</a></strong>						
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

