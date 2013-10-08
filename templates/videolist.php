<?php session_start();?>
<!--div class="span2 article-tree">
  <div class="sidebar-nav">
	<ul class="nav nav-list" data-spy="affix" data-offset-top="1000">
	  <li class="nav-header">My Keywords</li>
	  <li><a href="" ng-click="keyword='XSplit';processItems()">XSplit</a></li>
	  <li><a href="" ng-click="keyword='XSplit Crack';processItems()">XSplit Crack</a></li>
	  <li><a href="" ng-click="keyword='XSplit Full Version';processItems()">XSplit Full Version</a></li>
	  <li><a href="" ng-click="keyword='XSplit Broadcaster';processItems()">XSplit Broadcaster</a></li>
	  <li class="nav-header">Mode</li>
	  <li><a href="" ng-click="keyword='';data = []">YouTube Live Search</a></li>
	  <li><a href="#/rank">Database(Rated) Search</a></li>
	</ul>
  </div>
</div-->

<div class="span12 container content-area">	
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<form ng-submit="processItems()">
					<div class="control-group">
					  <div class="controls span6">
						<div class="input-append span8">
						  <input type="text" name="search_query" class="span12" required placeholder="Search YouTube" ng-model="keyword">
						  <button class="btn" type="submit">&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-search"></i> &nbsp;&nbsp;&nbsp;&nbsp;</button>
						</div>													
					  </div>					 
					  <div class="controls span3">
						<span class="span4">Sort By:</span>
						<select class="span7" ng-model="currentSort" ng-options="item as item.sortname for item in sortBy"></select>
					  </div>					  
					  <div class="controls span3" ng-hide="true">
						<span class="span4">Filter By:</span>
						<select class="span7" ng-disabled="!dbMode" ng-model="filterBy.ratings" ng-options="i.id as i.name for i in ratings"  ng-change="filterByRate()">
							<option value="">--None--</option>
						</select>
					  </div>
					</div>					
				</form>			
			</div>					
		</div>
	</div>
	<div class="container">
		<span ng-show="data.length>0&&!dbMode">
			Search Result from <span class="label label-success">YouTube</span> 
		</span>
	</div>
	<hr/>
	<div class="container row-hover">
		<div ng-show="filteredData.length<=0"  class="center header"><img src="images/loading.gif"></img></div>
		<div ng-show="introText" class="center header">
		  <center><h3><small class="center">Go search in YouTube here!</small></h3></center>
		</div>
		<div class="row-fluid hoverable" ng-repeat="video in filteredData = (sortedData = ((data | orderBy:currentSort.sorttext:currentSort.reverse) | filter:filterBy) | filter:hideRated)" ng-class="{rated:video.ratings!=0}" ng-init="video.tempRating=video.ratings;dataLength=filteredData.length">
		  <div class="span2">
				<a title="Open Video in a new tab" href="{{ytLinkVideo}}{{video.id}}" target="_blank"><img class="thumbnail" ng-src="{{video.snippet.thumbnails.medium.url}}" width="200"></a><!--span ng-hide="dbMode">{{($index+1)+(currentPage-1)*(pageInfo.resultsPerPage)}}</span--></img>
		  </div>				
				<div class="span10">
					<span class="show-onhover pull-right">
						<a href="#responsive" role="button" class="btn btn-small" data-toggle="modal" ng-click="changeActiveRow(video)"><i class="icon-play-circle"></i> </a>
					</span>
					<strong><a href="#responsive" role="button" data-toggle="modal" ng-click="changeActiveRow(video)">{{video.snippet.title}}</a></strong><br/>
					<small>
						<ul class="metaInfo">
							<li>by: <a href="{{ytLinkChannel}}{{video.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{video.snippet.channelTitle}}</a></li>
							<li>&bull; <span ng-class="{snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{video.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
							<li>&bull; <span ng-class="{statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.statistics.viewCount | number}} Views</span></li>										
						</ul>
						<p><span class="hideOverflow descShort">{{video.snippet.description}}</span></p>
					</small>
					<small ng-hide="currentSort.sorttext==''">
						<p>
							<span ng-class="{statistics.likeCount:'label label-warning'}[currentSort.sorttext]">Likes: {{video.statistics.likeCount | number}}</span> | 
							<span ng-class="{statistics.dislikeCount:'label label-warning'}[currentSort.sorttext]">Dislikes: {{video.statistics.dislikeCount | number}}</span> | 
							<span ng-class="{statistics.favoriteCount:'label label-warning'}[currentSort.sorttext]">Favorites: {{video.statistics.favoriteCount | number}}</span> | 
							<span ng-class="{statistics.commentCount:'label label-warning'}[currentSort.sorttext]">Comments: {{video.statistics.commentCount | number}}</span>						
						</p>
					</small>
				</div>			
			<hr class="bs-docs-separator clearfix"/>
		</div>
		<div ng-show="data.length>0" class="pagination center">
		  <ul>
			<li ng-class="{true:'disabled',false:''}[currentPage==''||currentPage=='1']"><a href="" ng-disabled="currentPage==''||currentPage=='1'" ng-click="changePage('prev')">Prev</a></li>
			<li><a href="">{{currentPage}}</a></li>
			<li ng-class="{true:'disabled',false:''}[currentPage==''||nodata]"><a href="" ng-disabled="currentPage==''" ng-click="changePage('next')">Next</a></li>
		  </ul>
		</div>		
	</div>
	<!--pre>{{data | json}}</pre-->
</div>
	
<!-- Modal Definitions (tabbed over for <pre>) -->
<div id="responsive" class="modal hide fade" tabindex="-1" data-width="1000">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel" class<small>Rank Video</small></h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
      <div class="span7">
		<div class="videoWrapper">
			<!-- Copy & Pasted from YouTube -->			
			<iframe width="560" height="349" src="http://www.youtube.com/embed/{{activeRow.id}}?modestbranding=1&rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
      </div>
      <div class="span5 .has-indent">
		<h4 id="myModalLabel" class=""><a href="activeRow.videoId" target="_blank">{{activeRow.snippet.title}}</a></h4>
        <small>
			<ul class="metaInfo">
				<li>by: <a href="{{ytLinkChannel}}{{activeRow.snippet.channelId}}" target="_blank" class="linkSmallBlack">{{activeRow.snippet.channelTitle}}</a></li>
				<li>&bull; <span ng-class="{snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{activeRow.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
				<li>&bull; <span ng-class="{statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{activeRow.statistics.viewCount | number}} Views</span></li>										
			</ul>
			<p><span class="hideOverflow descShort">{{activeRow.snippet.description}}</span></p>
		</small>
		<h4>Rank</h4>
       <div class="row-fluid">
			<select id="tagSelect" class="span4" ng-model="activeRow.ratings" ng-options="i.categoryName as i.categoryName for i in categoryData" ng-change="video.ratingEdit=true"></select>			
			<textarea class="span9" rows="2" cols="40" ng-model="activeRow.note" ng-disabled="video.ratings==0"></textarea>					
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Close</button>
    <button type="button" class="btn btn-primary" ng-click="saveRating($index)" data-dismiss="modal" >Save changes</button>
  </div>
</div>