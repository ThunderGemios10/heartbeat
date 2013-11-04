function defaultController($scope, $rootScope, $location, sessionService, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {	
	$scope.filterSelection = [
		{id:"default", value:"All"}
		,{id:"youtube", value:"YouTube"}
		,{id:"divider", value:"divider"}		
	];
	$scope.selectedFilter = $scope.filterSelection[0];
	$scope.filterChange = function (idx) {
		$scope.selectedFilter = $scope.filterSelection[idx];
	}
	$rootScope.$watch('page',function(){
		console.log($rootScope.page);
		if($rootScope.page=="ytsearch") {
			$scope.filterByPage = "youtube";
		}
		else if($rootScope.page=="dbsearch") {
			$scope.filterByPage = "default";
		}
		angular.forEach($scope.filterSelection,function(filter){
			var keepGoing = true;
			if(keepGoing) {
				if($scope.filterByPage==filter.id) {
					$scope.selectedFilter = filter;
					keepGoing = false;
				}
			}
		});	
	});

	$scope.searchDelay = function(str) {
		console.log($scope.selectedFilter);
		if($scope.selectedFilter.id=='default') {
			$location.path("/search/"+str);
		}
		else if($scope.selectedFilter.id=='youtube') {
			$location.path("/live/"+str);
		}
	};
	// console.log($rootScope);
	// $scope.search = function(str) {
	// 	console.log(str+'searchFundtion');
	// 	// $location.path("/rank/"+str);
	// };
	$scope.$on('$routeChangeSuccess', function () {
		var path = $location.path();
		if(path=='/search' || path=='/search/'){
			$scope.keyword=="";
		}
	});

	databaseService.getGroups().then(function(result){
  		// // console.log('sidebarNav');
  		console.log($scope.groups);
  		
  		$scope.groups = result;
  		angular.forEach(result,function(group){
  			console.log(group);
  			
			$scope.filterSelection.push({id:group.groupId, value:group.groupAltName});			
		});	

  	});
	
	$scope.$on('$routeChangeSuccess', function () {
		if($scope.groups) {
			$scope.routeParam = $location.path().split("/")[2];
	        angular.forEach($scope.groups,function(group){
				if($scope.routeParam==group.groupId) {
					$scope.selectedFilter = {id:group.groupId, value:group.groupAltName};
				}
			});
		}        
    });
	
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

	sessionService.getByKey('channelUsername').then(function(result){
		// console.log('channelUsername');
		// console.log(result);
	});
}