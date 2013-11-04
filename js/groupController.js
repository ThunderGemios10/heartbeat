function groupController($scope, $window,$q, $location, databaseService, $rootScope, $routeParams, $http, DataService, sessionService, $filter, $resource) {
	//get groupId
	$scope.groupId = $routeParams.groupId; //end-get groupId
	//pagination
	$scope.maxCount = 1000;
	$scope.currentPage = 1;
	$scope.numPerPage = 10;
	$scope.maxSize = 5;	//end-pagination	
	$scope.itemPerPage = 10;
	$scope.groupInfo = {
		// description:'a recommendation revenue.'
	}
	$scope.tabs = [
		{title:'Top',active:true}
		,{title:'Latest',active:false}
		,{title:'Channels',active:false}
	];
	$scope.getGroupVideo = function(groupId,filter,start,limit){
		databaseService.getGroupVideo(groupId,filter,start,limit).then(function(result){
			$scope.dbData = result.videos;
			console.log(result.videos);
			$scope.maxCount = result.maxcount;
		});
	}
	$scope.getGroupVideoCount = function(groupId){
		// databaseService.getGroupVideoCount(groupId).then(function(result){
		// 	$scope.maxCount = result.maxcount;
		// });
	}
	$scope.getGroupInfo = function () {
		databaseService.getGroupInfo($scope.groupId).then(function(result){
			$scope.groupInfo = result;
			console.log(result);
		});		
	}
	$scope.getGroups = function () {	
		databaseService.getGroups().then(function(result){
			$scope.subscribedGroup = result;
			// deferred.resolve(result);
		});
		// return deferred.promise;
		$scope.getGroupInfo();
	}
	$scope.getGroups();
	// $scope.getGroupVideo($scope.groupId,'top',0,$scope.itemPerPage);
	$scope.getGroupVideoCount($scope.groupId);
	var ready = false;
	$scope.$watch('currentPage',function(val){
		console.log(val+"&&"+ready);
		if(val>0) {
			// console.log('nice');
			$scope.getGroupVideo($scope.groupId,'top',(val-1),$scope.itemPerPage);
		}
		ready = true;
	},true);
}