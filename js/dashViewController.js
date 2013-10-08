function dashboardController($scope, $routeParams, $rootScope, $http, youtubeService, DataService, sessionService, databaseService, $filter,$resource) {
	console.log('dashboard');
	sessionService.getCurrentChannel().then(function(result){
		$scope.channelId = result.id;
		$scope.channelInfo_yt = result;
		databaseService.getUserChannel($scope.channelId).then(function(result){
			console.log(result);
			if(result.err) {
				youtubeService.getChannelUsername($scope.channelId).then(function(result){
					console.log(result);
					$scope.channelInfo_yt.channelUsername = result;
					databaseService.saveUserChannel($scope.channelInfo_yt).then(function(result){	
						console.log(result);
						if(!result.err) {
							$scope.channelInfo = result;
							$scope.loadFeed();
						}
					});
				});
			}
			else {				
				$scope.channelInfo = result;				
				$scope.loadFeed();			
			}
		});
	});
	$scope.loadFeed = function() {
		databaseService.getRankedVideoByAll().then(function(result){
			var videoList = [];
			$scope.rankedVideos = [];
			$scope.videoList = result;
			angular.forEach(result,function(video){
				videoList.push({videoId:video.videoId});
			});
			$scope.videoListToPass = videoList;
			databaseService.getVideo($scope.videoListToPass).then(function(result){				
				angular.forEach(result,function(item){
					var keepGoing = true;
					angular.forEach($scope.videoList,function(list){
						if(keepGoing) {
							// console.log('item.videoId==list.videoId');
							// console.log(item.videoId+"=="+list.videoId);
							if(item.videoId==list.videoId){
								item.tagDateModified = Date.parse(list.dateModified);
								item.taggerEmail = list.useremail;
								console.log(list.useremail);
								keepGoing = false;
								databaseService.getVideoTagsFeed(item.videoId,list.useremail).then(function(vidtags){
									console.log(vidtags);
									databaseService.getVideoTagsFeed(item.videoId,list.useremail).then(function(vidtags){

									});
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
									

									$scope.rankedVideos.push(item);									
								});
							}
						}
					});
				});							
			});
		});
	}
}