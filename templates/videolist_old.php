<div class="menu">
	<form name="search" id="search" action="" method="post" ng-submit="processItems()" class="navbar-search">
		<input id="txtSearch" name="txtSearch" type="text" class="search-query" placeholder="Search" ng-model="keyword"/>
		<div class="icon-search-live" ng-class="{'icon-search-db':dbMode}"></div>
		<span class="txtDb" ng-show="dbMode">Results from Database</span><span class="txtLive" ng-hide="dbMode">Live Search To YouTube</span>
		&nbsp;&nbsp;|&nbsp;&nbsp;No. of items: <select ng-hide="dbMode" ng-model="currentItem" ng-change="processItems()" ng-options="item.number for item in listQuantity" class="item-query number" ng-disabled="dbMode"></select>
		<span ng-show="dbMode">{{filteredData.length}}</span>
		&nbsp;&nbsp;&nbsp; Sort by: <select ng-model="currentSort" ng-options="item as item.sortname for item in sortBy" class="item-query sort"></select>
		&nbsp;&nbsp;&nbsp;Filter by: <select ng-model="filterBy.ratings" ng-options="i.id as i.rate for i in (ratings)" class="item-query sort" ng-show="dbMode" ng-change="filterByRate()">
											<option value="">--None--</option>
									 </select>
									 
									 <span ng-hide="dbMode">--None--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>	
		<div id="ck-button">
		   <label>
			  <input type="checkbox" ng-model="hideRated.isRated">
				<div class="text">Hide Rated</div>
		   </label>
		</div>
	</form>
</div>
<div class="items">
	<ul id="vidsection">
		<li class="header">
			<span class="image">&nbsp;</span>
			<span class="name">Title</span>
			<span class="description">Description</span>
			<span class="rating">Rating</span>
			<span class="ratingComment">Note</span>
			<span class="ratedate">Date</span>
		</li>
		<div ng-show="loading" class="loadingDiv"><img src="images/loadinggif_blue.gif"></img></div>
		<div ng-show="nodata" class="nodataDiv">No result in your query.</div>
		<li ng-repeat="video in filteredData = (sortedData = ((data | orderBy:currentSort.sorttext:currentSort.reverse) | filter:filterBy) | filter:hideRated)" ng-class="{rated:video.ratings!=0}">	
			<span class="image"><a title="Open Video in a new tab" href="{{ytLinkVideo}}{{video.id}}"  target="_blank"><img ng-src="{{video.snippet.thumbnails.medium.url}}" width="120"></a><span>{{($index+1)+(currentPage-1)*(pageInfo.resultsPerPage)}}</span></img></span>
			<span class="name"><a title="Open Video in a new tab" href="{{ytLinkVideo}}{{video.id}}" target="_blank">{{video.snippet.title}}</a>
				<br/>Views: {{video.statistics.viewCount | number}}
				<br/>Like: {{video.statistics.likeCount | number}}
				<br/>Dislike: {{video.statistics.dislikeCount | number}}
				<br/>Favorites: {{video.statistics.favoriteCount | number}}
				<br/>Comments: {{video.statistics.commentCount | number}}
				<br/>by: <a href="{{ytLinkChannel}}{{video.snippet.channelId}}" target="_blank">{{video.snippet.channelTitle}}</a>
				<br/>on: {{video.snippet.publishedAt | date:'MMM-d-yyyy HH:mm:ss a'}}
			</span>
			<span class="description" ng-init="video.desc=false">
				<span title="Click to collapse/expand"  ng-class="{true:'descExpand',false:'descCollapse'}[video.desc]" ng-click="video.desc=!(video.desc)">{{video.snippet.description}}</span>
				<!--span title="Click to collapse" ng-show="video.desc" ng-click="video.desc=false">{{video.snippet.description}}</span-->
			</span>
			<span class="rating"><!-------------BUTTON RATING------------>
				<a href="" ng-click="video.ratingEdit=true;video.tempRating=video.ratings;video.tempNote=video.note" ng-show="!(video.ratingEdit)" ng-class="{true:'ratedButton',false:'unratedButton'}[video.ratings>0]">{{rateName(video.ratings)}}</a>
				<select ng-model="video.ratings" ng-options="i.id as i.rate for i in ratings" ng-change="video.ratingEdit=true" ng-show="video.ratingEdit"></select>				
			</span>
			<span class="ratingComment">
				<span ng-show="!(video.ratingEdit)&&video.ratings!=0">"{{video.note}}"</span>		
				<textarea ng-show="video.ratingEdit" rows="3" cols="40" ng-model="video.note" ng-disabled="video.ratings==0"></textarea>
				<a href="" class="btnSave" ng-show="video.ratingEdit" ng-click="saveRating($index)">Save</a>&nbsp;
				<a href="" class="btnCancel" ng-show="video.ratingEdit" ng-click="video.ratingEdit=false;video.ratings=video.tempRating;video.note=video.tempNote">Cancel</a>				
			</span>
			<span class="ratedate" ng-show="!(video.ratingEdit)">{{video.postdate | date:'MMM-d-yyyy h:mm:ss a'}}</span>
		</li>		
	</ul>
</div>
<div class="footer">
	<span ng-show="total">Total: {{data.length}}</span>
	<div class="pagination">
		<!--span class="page active">4</span-->
		<button href="" class="page" ng-class="{true:'active',false:'gradient'}[currentPage==''||currentPage=='1']" ng-disabled="currentPage==''||currentPage=='1'" ng-click="changePage('prev')">prev</button>
		<button href="" class="page gradient" ng-show="currentPage">{{currentPage}}</button>
		<!--a href="" class="page gradient">{{pageInfo.totalResults/10}}</a-->
		<button href="" class="page" ng-class="{true:'active',false:'gradient'}[currentPage==''||nodata]" ng-disabled="currentPage==''" ng-click="changePage('next')">next</button>
	</div>
</div>
<p ng-show="data.length>0">Total Result: {{pageInfo.totalResults | number}} | Page {{currentPage}} of {{totalPages() | number:0}}</p>
<pre>{{datas | json}}</pre>

<div class="dim" ng-show="dim"><img src="images/loadinggif_blue.gif"></div>