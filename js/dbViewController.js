function dbViewController($scope, $window, $location, databaseService, $rootScope, $routeParams, $http, DataService, sessionService, $filter, $resource) {
	$scope.ytLinkVideo = DataService.ytLink_video;
	$scope.ytLinkChannel = DataService.ytLink_channel;
	$scope.currentPage = 1;
	$scope.numPerPage = 10;
	$scope.maxSize = 5;		
	$scope.username = "";
	$scope.useremail = "";
	$scope.paginated = 0;
	$rootScope.page = "list";
	$scope.showMore = false;
	$scope.searchFor = $routeParams.keyword;
	$scope.setPage = function (pageNo) {
		$scope.currentPage = pageNo;
	};
	$scope.bigNoOfPages = 18;
	$scope.bigCurrentPage = 1;    
	sessionService.getCurrentUser().then(function(user){
		$scope.username = user.name;
		$scope.useremail = user.email;		
	});
	$scope.paginated = [];
	$scope.changeActiveRow = function(row) {
		// $scope.activeRow = row;
	};
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
	$scope.currentSort=$scope.sortBy[0];	//current selected in sort by
	$scope.loadRatings = function(){
		$http({method: 'POST',url:'model/settings_model.php',data: {get:'true'},headers:{'Content-Type': 'application/data'}}).success(function(data,status,headers,config){			
			$scope.categoryData = data;
		}).error(function(data,status,headers,config){});
	}
	$scope.filterByRate = function(i) {
		if(!($scope.filterBy))
			$scope.filterBy = "";
	};
	// $scope.go = function(path) {
	//   $location.path(path);
	// };
	$scope.resetPage = function(){
		$scope.currentPage = 1;		
	};
	$scope.numPages = function () {
		if($scope.paginated&&$scope.numPerPage){
			return Math.ceil($scope.paginated.length / $scope.numPerPage);
		}
		else
			return 1;
	};
	$scope.init = function(){
		$scope.loadRatings();
		$scope.db = [];	
		databaseService.searchVideo($scope.searchFor).then(function(result){
			console.log(result);
			if(result.length<1) 
				$scope.dbData = {notFound:true};
			else 
				$scope.dbData = result;
		});
		// $http({method: 'POST',url:'model/videorating_model_new.php',data: {all:true},headers:{'Content-Type': 'application/data'}}).success(function(dbData,status,headers,config){			
		// 	if(dbData.length>0) {
		// 		$scope.dbData = $filter('filter')(dbData,$scope.searchFor);
		// 		$scope.nodata = false;
		// 	}
		// 	else {
		// 		$scope.nodata = true;
		// 	}
		// }).error(function(data,status,headers,config){});
	};	
	$scope.init();
}