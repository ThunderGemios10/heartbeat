function usertagslistController($scope, $timeout, $location, $rootScope, $filter, sessionService, utility, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {
    $scope.loadlist = function () {    	
    	databaseService.getTopFreeTags(0,10).then(function(result){
    		// console.log(result);
    		$scope.toptags = result;
    	});
        databaseService.getTopCurrentUserFreeTags(0,10).then(function(result){
            console.log(result);
            $scope.usertags = result;
        });
    }
    $scope.timeago = function(dated){
    	return utility.timeago(dated);	
    }
    $scope.loadlist();
}