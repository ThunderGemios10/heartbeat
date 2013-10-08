function channelController($scope, $timeout, $location, $rootScope, $filter, sessionService, $routeParams, $http, databaseService, youtubeService, sessionService, DataService,$filter,$resource) {	 
    $scope.activeVideo = {};
    $scope.showMoreText = 'Show more';
    $scope.filteredVideo = [];
    $scope.timeago = function(dated){
        var date = humanized_time_span(dated);
        return date;
    }
    $scope.$watch('filteredVideo',function(){
    	if($scope.filteredVideo) {
    	 	if($scope.filteredVideo.length>0)
    			$scope.activeRow = $scope.filteredVideo[0];
    	 }
    },true);
	$scope.showMoreFunc = function() {
		console.log($scope.showMore);
		if($scope.showMore) {
			$scope.showMoreText="Show more";
			$scope.showMore=false;
		}
		else {
			$scope.showMoreText="Show less";
			$scope.showMore=true;
		}
	}
	$scope.getUserRankedVideos = function(){
		youtubeService.getVideosFromChannel().then(function(result){
			$scope.channelVideos = [];
			$scope.activeRow = {};
			$scope.activeRow.videoInfo = {};
			console.log('getVideosFromChannel()');
			console.log(result);
			var i = 0;
			angular.forEach(result.items,function(item){
				$scope.channelVideos[i] = {};
				$scope.channelVideos[i].video = item;
				var vidIds = [];
				vidIds.push(item.snippet.resourceId.videoId);
				youtubeService.getDetails(vidIds,i).then(function(outDetails){
					if(outDetails.index==0) {
						// $scope.activeRow.videoInfo = outDetails[0][0];
					}
					$scope.channelVideos[outDetails.index].videoInfo = outDetails[0][0];							
				});
				i++;
			});
		});
		
		databaseService.getRankedVideoByUser().then(function(result){			
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
							if(item.videoId==list.videoId){
								item.tagDateModified = Date.parse(list.dateModified);								
								databaseService.getVideoTags(item.videoId,"feed").then(function(vidtags){
									// console.log('console.log(vidtags);');
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
									$scope.rankedVideos.push(item);									
								});
							}
						}
					});
				});							
			});
		});
	}	
	$scope.getUserRankedVideos();
}