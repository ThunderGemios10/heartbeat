function adminController($scope, $timeout, $location, $rootScope, $filter, sessionService, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {
	$scope.init = function (id) {		
		databaseService.getAllAuthUser().then(function(user){
			console.log(user);
			$scope.userlist = user;
		});
	}
	$scope.changeSelected = function(row) {
		$scope.activeRow = row;
		$scope.show('view');
	}
	$scope.authtypes = [
		'admin'
		,'user'
		,'guest'
		,'domain'
	];
	$scope.status = [
		{name:'active',status:1}		
	];
	$scope.show = function(mode) {
		$scope.showAdd = false;
		$scope.showEdit = false;
		$scope.showView = false;
		if(mode=='add') {
			$scope.activeRow = {};
			$scope.showAdd=true;
		}
		else if(mode=='edit') {
			 $scope.editRow = angular.copy($scope.activeRow);			
			// console.log($scope.editRow);
			$scope.showEdit=true;
		}
		else if(mode=='view') {			
			$scope.showView=true;
		}
		else if(mode=='resetview') {
			$scope.resetView();
			$scope.showView=true;
		}		
		else if(mode=='cancelEdit') {		
			$scope.showView=true;
			// console.log(tempRow);
			// $scope.activeRow = tempRow;
		}
	}
	$scope.saveNewUser = function(newRow) {
		console.log(newRow);	
		databaseService.saveNewUser(newRow).then(function(response){
			console.log(response);
			$scope.init(newRow);
			$scope.activeRow = newRow;
			$scope.show('view');
		});
	}
	$scope.saveEditUser = function(editRow) {
		console.log(editRow);
		var id = editRow._id.$id;
		databaseService.saveEditUser(id,editRow).then(function(response){
			console.log(response);
			$scope.init(editRow);
			$scope.activeRow = editRow;
			$scope.show('view');
		});
	}
	$scope.init();
}