function defaultController($scope, $rootScope, $location, sessionService, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {
	$scope.searchDelay = function(str) {
		$location.path("/rank/"+str);
	};
	$scope.search = function(str) {
		console.log(str);
	};
	$scope.$on('$routeChangeSuccess', function () {
		var path = $location.path();
		if(path=='/rank' || path=='/rank/'){
			$scope.keyword=="";
		}
	});

	console.log($rootScope.channelPhoto);
	
	$rootScope.page = "home";
	$scope.page = $rootScope.page;
	$scope.sortBy = [
		{sortname: '--Sort--',sorttext:'',reverse:''}
		,{sortname: 'Video Name +',sorttext:'videoInfo.snippet.title',reverse:false}
		,{sortname: 'Video Name -',sorttext:'videoInfo.snippet.title',reverse:true}
		,{sortname: 'Video Upload Date +',sorttext:'videoInfo.snippet.publishedAt',reverse:true}
		,{sortname: 'Video Upload Date -',sorttext:'videoInfo.snippet.publishedAt',reverse:false}
		,{sortname: 'Views +',sorttext:'videoInfo.statistics.viewCount',reverse:true}
		,{sortname: 'Views -',sorttext:'videoInfo.statistics.viewCount',reverse:false}
		,{sortname: 'Likes +',sorttext:'videoInfo.statistics.likeCount',reverse:true}
		,{sortname: 'Likes -',sorttext:'videoInfo.statistics.likeCount',reverse:false}		
		,{sortname: 'Dislikes +',sorttext:'videoInfo.statistics.dislikeCount',reverse:true}
		,{sortname: 'Dislikes -',sorttext:'videoInfo.statistics.dislikeCount',reverse:false}
		,{sortname: 'Favorites +',sorttext:'videoInfo.statistics.favoriteCount',reverse:true}
		,{sortname: 'Favorites -',sorttext:'videoInfo.statistics.favoriteCount',reverse:false}
		,{sortname: 'Comments +',sorttext:'videoInfo.statistics.commentCount',reverse:true}
		,{sortname: 'Comments -',sorttext:'videoInfo.statistics.commentCount',reverse:false}
		,{sortname: 'Postdate +',sorttext:'comments.postdate.sec',reverse:true}
		,{sortname: 'Postdate -',sorttext:'comments.postdate.sec',reverse:false}
	];
	$scope.currentSort=$scope.sortBy[0];

	sessionService.getCurrentChannelUsername
}