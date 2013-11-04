	<?php session_start();?>
		<div sidebar-nav active="" col="2"></div>
		<div class="col-md-10 pull-left">			
			<div>
				<!-- <div class="center header" ng-hide="dbData.notFound || paginated"><img src="images/loading.gif"></img></div> -->
				<div ng-hide="!dbData.notFound" class="center header">
				  <center><h3><small class="center">There's no search result for '{{searchFor}}'.</small></h3></center>
				</div>
				<div ng-show="!dbData.notFound" class="center header">
				 	<p>Search result for '<span class="color-red">{{searchFor}}</span>' from heartbeat indexed videos.</p>
				</div>
			</div>			
			<!-- <div> -->
			<div id="accordion_detailedview" class="accordion row hoverable has-padding-bottom-sm" ng-repeat="video in paginated = (filteredData = (sortedData = (data1 = ( data2 = (dbData | filter:keyword) | orderBy:currentSort.sorttext:currentSort.reverse) | filter:filterBy) | filter:hideRated)) | paginate:currentPage:numPerPage" ng-class="{rated:video.ratings!=0}">		  		  
				<div class="col-md-7">
					<div class="col-md-4 no-padding">
						<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="200"></img></a>				
					</div>
					<div class="col-md-8 no-padding">					
						<strong><a title="Open Video in a new tab" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></strong>		
						<small>
							<ul class="metaInfo">
								<li>by: <a href="{{ytLinkChannel}}{{video.videoInfo.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{video.videoInfo.snippet.channelTitle}}</a></li>
								<li>&bull; <span ng-class="{videoInfo.snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
								<li>&bull; <span ng-class="{videoInfo.statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.statistics.viewCount | number}} Views</span></li>										
							</ul>							
							<div class="ellipsis">
								<div class="ellipsis-sm">
									<p><span class="hideOverflow-2">{{video.videoInfo.snippet.description}}</a></span>	
								</div>
							</div>	
						</small>							
					</div>		
				</div>
				<!-- <hr class="col-md-7 bs-docs-separator" /> -->
			</div>
		<!-- 	{{paginated.length}}<br/>
			{{currentPage}}<br/>
			{{maxSize}}<br/> -->
			<div ng-show="paginated.length>0">
				<pagination
					total-items="paginated.length" 
					page="currentPage" 
					max-size="maxSize" 
					class="pagination-small" 
					boundary-links="true">
				</pagination>
			</div>
			<!-- </div>			 -->
		</div>		
		<!-- <pre>{{dbData | json}}</pre -->

