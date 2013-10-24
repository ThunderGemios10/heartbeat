function dashboardController($scope, $routeParams, $rootScope, $http, youtubeService, utility, DataService, sessionService, databaseService, $filter, $resource, $location) {
	console.log('dashboard');
	$scope.timeago = function(dated){
        var date = humanized_time_span(dated);
        return date;
    }
	sessionService.getCurrentChannel().then(function(result){
		$scope.channelId = result.id;
		$scope.channelInfo_yt = result;
		databaseService.getUserChannel($scope.channelId).then(function(result){
			console.log(result);
			if(result.err) {
				// youtubeService.getChannelUsername($scope.channelId).then(function(result){
					// console.log(result);
					// $scope.channelInfo_yt.channelUsername = result;
					// $rootScope.channelUsername = result;
					databaseService.saveUserChannel($scope.channelInfo_yt).then(function(result){	
						console.log(result);
						if(!result.err) {
							$scope.channelInfo = result;
							$scope.loadFeed();
						}
					});
				// });
			}
			else {
				$scope.channelInfo = result;				
				$scope.loadFeed();			
			}
		});
	});
	$scope.ajaxCounter = -1;
	$scope.loadFeed = function() {
		databaseService.getRankedVideoByAll().then(function(result){
			var videoList = [];

			$scope.tempRankedVideos = [];
			$scope.videoList = result;
			angular.forEach(result,function(video){
				videoList.push({videoId:video.videoId});
			});
			$scope.videoListToPass = videoList;

			databaseService.getVideo($scope.videoListToPass).then(function(result){
				$scope.ajaxCounter = result.length;
				angular.forEach(result,function(item){
					var keepGoing = true;
					angular.forEach($scope.videoList,function(list){
						if(keepGoing) {
							// console.log('item.videoId==list.videoId');
							// console.log(item.videoId+"=="+list.videoId);
							if(item.videoId==list.videoId){
								item.tagDateModified = Date.parse(list.dateModified);
								item.taggerEmail = list.useremail;
								// console.log(list.useremail);
								keepGoing = false;
								databaseService.getVideoTagsFeed(item.videoId,list.useremail).then(function(vidtags){
									// console.log(vidtags);
									item.videotags = vidtags;
									item.groupedvideotags = {};
									item.groupedvideotags['primary'] = [];
									item.groupedvideotags['tags'] = [];
									item.groupedvideotags['copyright'] = [];
									item.groupedvideotags['language'] = [];
									angular.forEach(vidtags,function(tagstype){
										if(tagstype.type==1) {
											item.groupedvideotags['primary'].push(tagstype);	
										}
										else if(tagstype.type==2) {
											item.groupedvideotags['tags'].push(tagstype);	
										}
										else if(tagstype.type==3) {
											item.groupedvideotags['copyright'].push(tagstype);	
										}
										else if(tagstype.type==4) {
											item.groupedvideotags['language'].push(tagstype);	
										}
									});
									databaseService.getAuthUserByEmail(list.useremail).then(function(result){
										item.taggerInfo = result[0];
										$scope.tempRankedVideos.push(item);
									});													
								});
							}
						}
					});
				});							
			});
		});
	}
	$scope.postrank = function () {
		$scope.videoIdToRank = utility.getParameterByName($scope.urltorank,"v");
		$scope.newLocation = '/play/'+$scope.videoIdToRank;
		$location.path($scope.newLocation);
	}
	$scope.$watch('[tempRankedVideos, ajaxCounter]',function(value){
		if(value[0]) {
			if(value[0].length==value[1]) {			
				$scope.rankedVideos = $scope.tempRankedVideos;
			}
		}	
	},true);
}