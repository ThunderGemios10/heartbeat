function youtubeSearchController($scope, $window, youtubeService, $routeParams, $location, databaseService, $rootScope, $routeParams, $http, DataService, sessionService, $filter, $resource) {
	$scope.keyword = $routeParams.keyword;
	$scope.pageToken = "";
	console.log($rootScope.page);
	if(!$routeParams.keyword) {
		$location.path('!/dashboard').replace();
	}
	$scope.ytLinkVideo = DataService.ytLink_video;
	$scope.ytLinkChannel = DataService.ytLink_channel;
	$scope.currentPage = 1;
	$scope.numPerPage = 10;
	$scope.maxSize = 1;		
	$scope.username = "";
	$scope.useremail = "";
	$scope.paginated = 0;
	$rootScope.page = "ytsearch";
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
	$scope.filterByRate = function(i) {
		if(!($scope.filterBy))
			$scope.filterBy = "";
	};
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
	$scope.processSearch = function(pageToken,direction){
		youtubeService.searchYouTube($scope.keyword,pageToken, true).then(function(result) {
			$scope.result = result;
			$scope.data = result.items;
			$scope.prevPageToken = result.prevPageToken;
			$scope.nextPageToken = result.nextPageToken;
			$scope.pageInfo = result.pageInfo;
			if(result.items) {
				if(direction=="next") {
					$scope.currentPage++;
				}
				else if(direction=="prev") {			
					$scope.currentPage--;
				}
			}
		});
	};	
	$scope.processSearch();
	$scope.changePage = function(direction) {	
		if(direction=="next") {
			$scope.processSearch($scope.nextPageToken);					
		}
		else if(direction=="prev") {			
			$scope.processSearch($scope.prevPageToken,direction);
		}
	}
}