function ranklaterController($scope, $timeout, $location, $rootScope, $filter, sessionService, utility, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {	 
    $scope.ranklist = [];
    $scope.loadlist = function () {
    	// console.log('ertertertert');
    	databaseService.getRankLaterByUser().then(function(result){
    		console.log(result);
    		$scope.ranklist = result;
    	});   	
    }
    $scope.ranked = function (ranked) {
        if(ranked) {
            return "Ranked";
        }
        else {
            return "Rank now";
        }
    }
    $scope.timeago = function(dated){
    	return utility.timeago(dated);	
    }
    $scope.loadlist();
}