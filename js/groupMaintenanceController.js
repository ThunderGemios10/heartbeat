function groupMaintenanceController($scope, $window, $q, $upload, utility, $location, databaseService, $rootScope, $routeParams, $http, DataService, sessionService, $filter, $resource) {
	$scope.timeago = function(dated){
        var date = humanized_time_span(dated);
        return date;
    }
	$scope.refresh = function (argument) {
		databaseService.getGroups().then(function(result){
			$scope.createdGroup = result;
		});
	}	
	$scope.manageMode = ($location.path().split("/")[1])=="managegroup"?true:false;
	 $scope.onFileSelect = function($files,id) {
	    //$files: an array of files selected, each file has name, size, and type.
	    for (var i = 0; i < $files.length; i++) {
	      var $file = $files[i];
	      $upload.upload({
	        url: 'model/image_upload.php', //upload.php script, node.js route, or servlet upload url
	        // headers: {'headerKey': 'headerValue'}, withCredential: true,
	        data: {filename: id},
	        file: $file,
	        //fileFormDataName: myFile, //(optional) sets 'Content-Desposition' formData name for file
	        progress: function(evt) {
	          console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
	        }
	      }).success(function(data, status, headers, config) {
	        // file is uploaded successfully
	        // console.log($file['name']);
	        $scope.editGroup(id,"banner",id+"."+utility.getFileExtension($file['name']));
	      })
	      //.error(...).then(...); 
	    }
	  }
	$scope.groupType = [
		{id:"1", name:"YouTube Network"}
		,{id:"2", name:"Heartbeat Circle"}
		,{id:"0", name:"Others"}
	];
	$scope.getGroupTypeById = function (value) {
		var returnName = "";
		angular.forEach($scope.groupType,function (group) {
			// console.log(group.id==value);
			// console.log(group.id+"=="+value);
			if(group.id==value) {
				// console.log();
				returnName = group.name;
			}
		});
		return returnName;
	}
	$scope.showNewGroup = false;
	$scope.show = function () {
		$scope.showNewGroup = true;
	}
	$scope.hide = function () {
		$scope.showNewGroup = false;
	}
	$scope.changeTab = function (argument) {
		$scope.showTab = argument;
		// alert($scope.showTab);
	}
	$scope.deactivate
	$scope.exist = false;
	$scope.grouptype = $scope.groupType[0];
	$scope.save = function (newgroup) {
		databaseService.saveGroup(newgroup).then(function(result){
			console.log(result);
			if(result.response) {				
				$scope.refresh();
			}
			else {
				$scope.exist = true;
			}
		});
	}
	
	$scope.editGroup = function (id,editField,bannername) {
		databaseService.editGroup(id,editField,bannername).then(function(result){
			console.log(result);
			$scope.refresh();
		});
	}
	$scope.refresh();






	/********INDIVIDUAL GROUP VIEW****************************************************/
	$scope.getChannelByGroup = function (argument) {
		databaseService.getChannelByGroupId(argument).then(function(result){
			console.log(result);
			$scope.channelList = result;
			// $scope.getChannelByGroup(argument);
		});	
	}
	$scope.getVideoByGroup = function (argument) {
		databaseService.getVideoByGroup(argument).then(function(result){
			console.log(result);
			$scope.videoList = result;
			// $scope.getChannelByGroup(argument);
		});	
	}
	$scope.getGroupInfo = function (groupId) {
		databaseService.getGroupInfo(groupId).then(function(result){
			$scope.groupInfo = result;
			$scope.getChannelByGroup(groupId);
			$scope.getVideoByGroup(groupId);
		});	
	}
	if($routeParams.groupId) {
		// console.log($routeParams.groupId);
		$scope.activeGroupId = $routeParams.groupId;
		$scope.getGroupInfo($routeParams.groupId);
	}
	$scope.add = function () {
		var channelId = '';
		var groupId = $scope.groupInfo.groupId;

		if($scope.addNetworkPick=='id') {
			// console.log('qwe');
			channelId = $scope.channelId;
			if(channelId!="") {
				databaseService.addGroupChannel(channelId,groupId).then(function(result){
					$scope.getChannelByGroup($scope.activeGroupId);
				});	
			}
				
		}
		else if($scope.addNetworkPick=='username') {
			console.log($scope.addNetworkPick+", "+$scope.channelUsername);
			databaseService.getChannelIdByChannelUsername($scope.channelUsername).then(function(result){					
				console.log(result);
				databaseService.addGroupChannel(result,groupId).then(function(result){
					$scope.getChannelByGroup($scope.activeGroupId);
				});
			});				
		}
		
	}
	$scope.addfromcsv = function (argument) {
		var i=0;
		var channelIdColumnNumber = 0;
		angular.forEach(argument[0],function (arg) {
			console.log(arg);
			if(arg=="Channel") channelIdColumnNumber=i;
			i++;
		});
		argument.splice(0,1);
		$scope.argLength = argument.length;
		$scope.completeCounter = 0;
		angular.forEach(argument,function (arg) {
			var channelId = arg[channelIdColumnNumber];
			if(channelId!=""&&channelId!=null) {
				databaseService.addGroupChannel(channelId,$scope.activeGroupId).then(function(result){
					$scope.completeCounter++;
					console.log($scope.completeCounter);
					// $scope.getChannelByGroup($scope.activeGroupId);
				});
			}
		});

		$scope.$watch("completeCounter",function (val) {
			if(val==$scope.argLength) {
				$scope.getChannelByGroup($scope.activeGroupId);
			}			
		});
		
		// console.log(channelIdColumnNumber);
		// if(channelId!="") {
		// 	databaseService.addGroupChannel(channelId,groupId).then(function(result){
		// 		$scope.getChannelByGroup($scope.activeGroupId);
		// 	});	
		// }
	}
	$scope.getCSV = function(content, completed) {
		if(completed) {
			$scope.addfromcsv(content);
			console.log(content);	
		}
		else {
		   console.log("not yet");
		}
	};
}