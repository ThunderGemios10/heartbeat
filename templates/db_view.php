<?php session_start();?>
	<div class="container">
		<div class="row">
			<div class="jumbotron no-border-radius whiteHover">
		        <h1>Start ranking videos!</h1>
		        <p>Heartbeat.tm is dating for YouTubers</p>
		        <p>We match people based on common interest, not boyfriend/girlfriend dating (but that may come later :-) </p>
		        <p>
		        	<a href="#!/comments">Comments & Suggestion Section! &raquo;</a>
				</p>
		        <p>
		          <a class="btn btn-lg btn-primary" href="">Connect your YouTube channel soon :)</a>
		        </p>
		      </div>
		</div>
		<div class="row" id="topList">
			<div class="container">
				<hr class="col-md-9"/> 
				<div class="col-md-2">
					<select  ng-disabled="!paginated" class="form-control" ng-model="currentSort" ng-options="item as item.sortname for item in sortBy"></select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="center header" ng-hide="dbData"><img src="images/loading.gif"></img></div>
				<div ng-show="introText" ng-hide="paginated" class="center header">
				  <center><h3><small class="center">There's no search result for '{{keyword}}'.</small></h3></center>
				</div>
			</div>			
			<div class="col-md-12">
				<div id="accordion_detailedview" class="accordion row hoverable" ng-repeat="video in paginated = (filteredData = (sortedData = (data1 = ( data2 = (dbData | filter:keyword) | orderBy:currentSort.sorttext:currentSort.reverse) | filter:filterBy) | filter:hideRated)) | paginate:currentPage:numPerPage" ng-class="{rated:video.ratings!=0}">		  		  
					<div class="col-md-2">
						<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="180"></img></a>				
					</div>
					<div class="col-md-6">					
						<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></strong>
						<span class="show-onhover pull-right">														
							<a class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-parent="#accordion_detailedview" href="#collapse_{{$index}}"><i class="glyphicon glyphicon-chevron-right"></i> </a>
						</span>
						<br/>
						<small>
							<ul class="metaInfo">
								<li>by: <a href="{{ytLinkChannel}}{{video.videoInfo.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{video.videoInfo.snippet.channelTitle}}</a></li>
								<li>&bull; <span ng-class="{videoInfo.snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
								<li>&bull; <span ng-class="{videoInfo.statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.statistics.viewCount | number}} Views</span></li>										
							</ul>
							<p><span class="hideOverflow descShort">{{video.videoInfo.snippet.description}}</span></p>
						</small>
						<small ng-hide="currentSort.sorttext==''">
							<p>
								<span ng-class="{videoInfo.statistics.likeCount:'label label-warning'}[currentSort.sorttext]">Likes: {{video.videoInfo.statistics.likeCount | number}}</span> | 
								<span ng-class="{videoInfo.statistics.dislikeCount:'label label-warning'}[currentSort.sorttext]">Dislikes: {{video.videoInfo.statistics.dislikeCount | number}}</span> | 
								<span ng-class="{videoInfo.statistics.favoriteCount:'label label-warning'}[currentSort.sorttext]">Favorites: {{video.videoInfo.statistics.favoriteCount | number}}</span> | 
								<span ng-class="{videoInfo.statistics.commentCount:'label label-warning'}[currentSort.sorttext]">Comments: {{video.videoInfo.statistics.commentCount | number}}</span>						
							</p>
						</small>						
					</div>
					<div class="col-md-4">
						<div id="collapse_{{$index}}" class="accordion-body collapse">
							<h5>Dashboard Info</h5>
							<hr/>
							<p>
								<span class="col-md-5">In any.TV playlist: </span>
								<span class="col-md-5">&nbsp;{{video.dashboardInfo[0].statistics.in_playlist}}</span>
							</p>
							<p>
								<span class="col-md-5">Country Code: </span>
								<span class="col-md-5">&nbsp;{{video.dashboardInfo[0].statistics.Country_Code}}</span>
							</p>
							<p>
								<span class="col-md-5">Country Name: </span>
								<span class="col-md-5">&nbsp;{{video.dashboardInfo[0].statistics.Country_Name}}</span>
							</p>
							<p>
								<span class="col-md-5">Video Offer: </span>
								<span class="col-md-5">&nbsp;{{video.dashboardInfo[0].statistics.Video_Offer}}</span>
							</p>
							<p>
								<span class="col-md-5">Video Clicks: </span>
								<span class="col-md-5">&nbsp;{{video.dashboardInfo[0].statistics.Video_Clicks}}</span>
							</p>
						</div>					
					</div>				
					<hr class="col-md-6 bs-docs-separator" />
				</div>
				<pagination 
					total-items="paginated.length" 
					page="currentPage" 
					max-size="maxSize" 
					class="pagination-small" 
					boundary-links="true">
				</pagination>
			</div>			
		</div>		
		<!-- <pre>{{dbData | json}}</pre -->
	</div>

