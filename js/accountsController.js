function accountsController($scope, $routeParams, $http, DataService,$filter) {
	var changeLocation = function(url) {
		if(true) {
			window.location = url;
		}
		else {
			//only use this if you want to replace the history stack
			$location.path(url).replace();

			//this this if you want to change the URL and add it to the history stack
			//$location.path(url);
			$scope.$apply();
		}
	};
	$http({method: 'POST',url:'model/session_model.php',data: {userlevel:''},headers:{'Content-Type': 'application/data'}}).success(function(user,status,headers,config){		
		if(user=='false') changeLocation('#pageNotFound');
	}).error(function(data,status,headers,config){});
}