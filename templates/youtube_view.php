<?php session_start();?>
		<div sidebar-nav active="" col="2"></div>
		<div class="col-md-10 pull-left">			
			<div>
				<!-- <div class="center header" ng-hide="dbData.notFound || paginated"><img src="images/loading.gif"></img></div> -->
				<div ng-hide="!dbData.notFound" class="center header">
				  <center><h3><small class="center">There's no search result for '{{searchFor}}'.</small></h3></center>
				</div>
				<div ng-show="!dbData.notFound" class="center header">
				 	<p>Search result for '<span class="color-red">{{searchFor}}</span>' from YouTube.</p>
				</div>
			</div>			
			<!-- <div> -->
			<div id="accordion_detailedview" class="accordion row hoverable has-padding-bottom-sm" ng-repeat="video in paginated = data | paginate:currentPage:numPerPage" ng-class="{rated:video.ratings!=0}">
				<!-- <pre>{{video | json}}</pre> -->
				<div class="col-md-7">
					<div class="col-md-4 no-padding">
						<a title="Play/Rate Video!" href="#!/play/{{video.id}}"><img ng-src="{{video.snippet.thumbnails.medium.url}}" width="200"></img></a>				
					</div>
					<div class="col-md-8 no-padding">					
						<strong><a title="Open Video in a new tab" href="#!/play/{{video.id}}">{{video.snippet.title}}</a></strong>		
						<small>
							<ul class="metaInfo">
								<li>by: <a href="{{ytLinkChannel}}{{video.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{video.snippet.channelTitle}}</a></li>
								<li>&bull; <span ng-class="{videoInfo.snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{video.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
								<li>&bull; <span ng-class="{videoInfo.statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.statistics.viewCount | number}} Views</span></li>										
							</ul>							
							<div class="ellipsis">
								<div class="ellipsis-sm">
									<p><span class="hideOverflow-2">{{video.snippet.description}}</a></span>	
								</div>
							</div>	
						</small>							
					</div>		
				</div>				
			</div>
			<span ng-show="paginated">Page: {{currentPage}} / {{pageInfo.totalResults}}</span>
			<div ng-show="paginated.length>0" class="col-md-7 pull-left">
				<!-- <pagination
					total-items="pageInfo.totalResults" 
					page="currentPage" 
					max-size="maxSize" 
					class="pagination-small" 
					boundary-links="true">
				</pagination> -->
				 <!-- <pagination total-items="pageInfo.totalResults" page="currentPage" max-size="1"></pagination> -->
				 <a href="" class="btn btn-success" ng-disabled="currentPage<=1" ng-click="changePage('prev')">&laquo; Prev</a>				 
				 <a href="" class="btn btn-success" ng-click="changePage('next')" data-loading-text="Loading...">Next &raquo;</a>				
			</div>		
		</div>	

