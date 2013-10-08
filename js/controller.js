function videoTrackController($scope, $routeParams, $http, DataService, sessionService,$filter,$resource) {
	//$scope.games = DataService.games;
	$scope.rawData = [];	
	$scope.data = [];  
	$scope.subdata2 = [];
	$scope.subdata1 = [];
	$scope.test = [];
	$scope.videoIds = [];
	$scope.keyword="";
	$scope.desc = true;
	$scope.dbData=[];
	$scope.dbVids=[];
	$scope.pageInfo = [];
	$scope.prev = "";	//for pagination vars
	$scope.next = "";
	$scope.currentPage = "";
	var keywordStr = "";
	var keyword = "";
	$scope.user = "";
	sessionService.getCurrentUser().then(function(user){		
		$scope.user = user;	
	});
	$scope.sortedData = [];
	$scope.introText=true;
	$scope.dataLength=0;
	$scope.filteredData = [];
	$scope.dynamicTooltip = "Hello, World!";
	$scope.dynamicTooltipText = "dynamic";
	$scope.htmlTooltip = "I've been made <b>bold</b>!";
	$scope.ytLinkVideo = DataService.ytLink_video;
	$scope.ytLinkChannel = DataService.ytLink_channel;
	$scope.changeActiveRow = function(row) {
		console.log(row);
		$scope.activeRow = row;
	};
	$scope.listQuantity = [ //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		{number: '1'}
		,{number: '2'}
		,{number: '5'}
		,{number: '10'}
		,{number: '20'}
		,{number: '50'}
	];
	$scope.currentItem = $scope.listQuantity[4]; //current selected in number filter
	$scope.sortBy = [
		{sortname: '--Default--',sorttext:'',reverse:''}
		,{sortname: 'Video Name +',sorttext:'snippet.title',reverse:false}
		,{sortname: 'Video Name -',sorttext:'snippet.title',reverse:true}
		,{sortname: 'Video Upload Date +',sorttext:'snippet.publishedAt',reverse:true}
		,{sortname: 'Video Upload Date -',sorttext:'snippet.publishedAt',reverse:false}		
		,{sortname: 'Date Rated +',sorttext:'postdate',reverse:true}
		,{sortname: 'Date Rated -',sorttext:'postdate',reverse:false}
		,{sortname: 'Note +',sorttext:'note',reverse:false}
		,{sortname: 'Note -',sorttext:'note',reverse:true}		
		,{sortname: 'Views +',sorttext:'statistics.viewCount',reverse:true}
		,{sortname: 'Views -',sorttext:'statistics.viewCount',reverse:false}
		,{sortname: 'Likes +',sorttext:'statistics.likeCount',reverse:true}
		,{sortname: 'Likes -',sorttext:'statistics.likeCount',reverse:false}		
		,{sortname: 'Dislikes +',sorttext:'statistics.dislikeCount',reverse:true}
		,{sortname: 'Dislikes -',sorttext:'statistics.dislikeCount',reverse:false}
		,{sortname: 'Favorites +',sorttext:'statistics.favoriteCount',reverse:true}
		,{sortname: 'Favorites -',sorttext:'statistics.favoriteCount',reverse:false}
		,{sortname: 'Comments +',sorttext:'statistics.commentCount',reverse:true}
		,{sortname: 'Comments -',sorttext:'statistics.commentCount',reverse:false}
	];
	$scope.currentSort=$scope.sortBy[0];	//current selected in sort by
	$scope.categoryData  = [];
	$scope.loadRatings = function(){
		$http({method: 'POST',url:'model/settings_model.php',data: {get:'true'},headers:{'Content-Type': 'application/data'}}).success(function(data,status,headers,config){
			$scope.categoryData = data;
		}).error(function(data,status,headers,config){});
	}
	$scope.dbMode = false;
	$scope.filterBy = [];	
	$scope.filterByRate = function(i) {
		if(!($scope.filterBy.ratings))
			$scope.filterBy = "";
	}
	$scope.showDesc = function(index) {//------------------------------------------------------------------------------------------------------------------------------------------------------------------
		if($scope.desc == "") $scope.desc = 'descHide'+index;
		else if($scope.desc == "descHide"+index) $scope.desc = 'descShow'+index;
		else if($scope.desc == "descShow"+index) $scope.desc = 'descHide'+index;	
	};
	$scope.rateName = function(rateId) {
		var name = "";
		angular.forEach($scope.ratings, function(rating){
			if(rateId==rating.id){
				name = rating.name;
			}
		});
		if(name=="")
			name="Rate This";
		return name;
	};
	$scope.getDetails = function (arrId,rateInfo,mode) {
		var returnData = [];
		var tags = [];
		$http({method: 'POST',url:'apirequest/youtube-api-video.php',data: {ids:arrId},headers:{'Content-Type': 'application/data'}}).success(function(info,status,headers,config){
				$http({method: 'POST',url:'model/videorating_model.php',data: {ids:arrId,tagsPerVid:''},headers:{'Content-Type': 'application/data'}}).success(function(tagsInfo,status,headers,config){
					// $scope.test = tagsInfo;			
					// console.log(tagsInfo);
					angular.forEach(info[0], function(temp){
						angular.forEach(rateInfo, function(vidRate){
							if(temp.id==vidRate.id){							
								var notRated=true;
								angular.forEach($scope.ratings, function(rate){	//compare videos to ratings
									if(vidRate.rate==rate.id){
										temp.ratings = vidRate.rate;
										temp.note = vidRate.note;
										temp.postdate = Date.parse(vidRate.postdate);		  //convert date to javascript date		
										temp.isRated = true;
										// alert(vidRate.ratedByName+" "+vidRate.ratedByEmail);
										temp.ratedByName = vidRate.ratedByName;
										temp.ratedByEmail = vidRate.ratedByEmail;
										notRated=false;
										//new Order of rating										
									}
								});
								if(notRated) {	//default value if not rated
									temp.ratings = "0";
									temp.note = "";	
									temp.postdate = "";
									temp.isRated = false;
									temp.ratedByName = false;
									temp.ratedByEmail = false;
								}
							}
						});
						// $scope.temp = tagsInfo[temp.id];
						temp.tagsCount = tagsInfo[temp.id];
						
						//convert json string to number(counts, because it is treated as string in orderBy)
						temp.statistics.viewCount = parseFloat(temp.statistics.viewCount);
						temp.statistics.likeCount = parseFloat(temp.statistics.likeCount);
						temp.statistics.dislikeCount = parseFloat(temp.statistics.dislikeCount);
						temp.statistics.favoriteCount = parseFloat(temp.statistics.favoriteCount);
						temp.statistics.commentCount = parseFloat(temp.statistics.commentCount);
						temp.snippet.publishedAt = Date.parse(temp.snippet.publishedAt); //convert date to javascript date
						returnData.push(temp);
					});
					if(mode=="live"){
						$scope.data = returnData;
						$scope.loading = false;		
					}
					else if(mode=="db"){
						$scope.dbData = returnData;
						if($scope.dbMode){
							$scope.data = $scope.dbData							
							$scope.loading = false;		
						}
						$scope.loading = false;
					}
				}).error(function(data,status,headers,config){});	
		}).error(function(data,status,headers,config){});	
	};
	
	$scope.init = function(){
		$scope.dbVids = [];		
		$http({method: 'POST',url:'model/videorating_model_new.php',data: {all:true},headers:{'Content-Type': 'application/data'}}).success(function(dbData,status,headers,config){			
			// console.log("ALL:");
			// console.log(dbData);
			if(dbData.length>0) {
				angular.forEach(dbData,function(db){
					$scope.dbVids.push(db.videoId);						
				});
				$scope.nodata = false;
				$http({method: 'POST',url:'model/videorating_model.php',data: {ids:$scope.dbVids,filtered:'true'},headers:{'Content-Type': 'application/data'}}).success(function(rateInfo,status,headers,config){																			
					// $scope.test = rateInfo;
					$scope.getDetails($scope.dbVids,rateInfo,"db");			
				}).error(function(data,status,headers,config){});
			}
			else {
				$scope.nodata = true;
			}			
		}).error(function(data,status,headers,config){});

	};
	$scope.processItem = function () {			//youtube api request ------------------------------------------------------------------------------------------------------------------------------------------------------------------		
		$scope.getDetails($scope.videoIds,"live");
	};
	$scope.processItems = function (pageToken,direction) {			//youtube api request ------------------------------------------------------------------------------------------------------------------------------------------------------------------		
		$scope.currentSort=$scope.sortBy[0];
		$scope.introText = false;
		if(!($scope.keyword==="")) {			
			keywordStr = $scope.keyword.substr(0,3);
			keywordStr = keywordStr.toLowerCase();
			keyword = $scope.keyword.substr(3);
			if(keywordStr=="db:"){
				$scope.loading=true;
				if(keyword==""|keyword==null){
					$scope.data = $scope.dbData;
					if($scope.dbData.length>0) $scope.loading=false;
				}
				else {
					$scope.data = $filter('filter')($scope.dbData,keyword);
					$scope.loading=false;
				}
				$scope.dbMode = true;					
			}
			else {			
				$scope.dbMode = false;
				$scope.data = [];
				$scope.nodata = false;
				$scope.filterBy = "";
				$scope.dFilterByRatings = true;
				$scope.loading=true;
				
				
				if(pageToken) {
					if(direction=="next") {
						$scope.currentPage++;
					}
					else {
						$scope.currentPage--;
					}
				}
				else {
					$scope.currentPage = 1;
					$scope.prev = "";
					$scope.next = "";
					pageToken = "";
				}		
				
				$http({method: 'POST',url:'apirequest/youtube-api-video.php',data: {
					query:$scope.keyword
					,maxResults:$scope.currentItem.number
					,pageToken:pageToken
					},header: {"Content-Type":"application/data"}}).success(function(data,status,headers,config){															
					
					// $scope.test = data;
					// if(!(pageToken)) {
						$scope.pageInfo = data.pageInfo;
					// }					
					$scope.prev = data.prevPageToken;
					$scope.next = data.nextPageToken;
					data = data.items;
					
					//after getting the list of videos on search, get the statistics of each video by id
					if(data.length<=0){
						$scope.nodata = true;
						$scope.loading = false;
					}
					else {
						//$scope.loading = true;
						var arrOfVideoId = [];
						var rawArrOfVideoId = [];
						$scope.videoIds = [];
						//$scope.test = data;
						angular.forEach(data, function(item) {	//get array of ids from the search result
							if(item.id.kind=="youtube#video"){
								arrOfVideoId.push('www.youtube.com/watch?v='+item.id.videoId);	
								$scope.videoIds.push(item.id.videoId);
							}
						});	
						//request for the information of the videos searched
						$http({method: 'POST',url:'model/videorating_model.php',data: {ids:$scope.videoIds},headers:{'Content-Type': 'application/data'}}).success(function(rateInfo,status,headers,config){																			
							// $scope.test = rateInfo;
							$scope.getDetails($scope.videoIds,rateInfo,"live");
						}).error(function(data,status,headers,config){});										
					}
				}).error(function(data,status,headers,config){});
			}
		}
	};
	//save rating function ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	$scope.saveRatings = function(){			
		var arrRatings = [];	
		angular.forEach($scope.data, function(newData){	//get array of ids from the search result	
			if(newData.ratings!=0&&newData.ratingEdit) {
				arrRatings.push({
					"videoId":newData.id
					,"rating":newData.ratings
					,"note":newData.note
				});
			}
		});
		$http({method: 'POST',url:'model/videorating_model.php',data: {vidRates:arrRatings},headers:{'Content-Type': 'application/data'}}).success(function(data,status,headers,config){		
				$scope.processItems();
		}).error(function(data,status,headers,config){});	
	};
	
	$scope.saveRating = function(row) {
		// var selectedRow = $scope.$eval('filteredData['+row+']');
		alert();
		var selectedRow = $scope.activeRow;
		var arrId = [];
		arrId[0] = selectedRow.ratings;
		var arrRatings = [];				
		var name = $scope.user.name;
		var email = $scope.user.email;
		arrRatings.push({
			"videoId":selectedRow.id
			,"channelId":selectedRow.snippet.channelId
			,"rating":selectedRow.ratings
			,"note":selectedRow.note
			,"username":name
			,"useremail":email
			,"snippet":selectedRow.snippet
			,"statistics":selectedRow.statistics
		});
		console.log(arrRatings);
		// currentDate = new Date();
		// $scope.sortedData[row].postdate = Date.parse(currentDate);
		// $scope.sortedData[row].ratedByName = $scope.username;
		// $scope.sortedData[row].ratingEdit = false;
		$http({method: 'POST',url:'model/videorating_model_new.php',data: {videosArr:arrRatings,mode:"live"},headers:{'Content-Type': 'application/data'}}).success(function(data,status,headers,config){					
			console.log("After Save:");
			console.log(data);
			if(data='true') {
				// $scope.init();
				// $scope.sortedData[row].postdate = Date.parse(currentDate);
				// $scope.sortedData[row].ratedByName = $scope.username;
				// $scope.sortedData[row].ratingEdit = false;
				// $scope.sortedData[row].isRated = true;
				// $scope.sortedData[row].isRated = true;
			}
		}).error(function(data,status,headers,config){});
	};
	$scope.changePage = function(direction) {			
		if(direction=="next")
			$scope.processItems($scope.next,"next");
		else
			if(!($scope.currentPage==''||$scope.currentPage==1)){
				$scope.processItems($scope.prev,"prev");
			}
	}
	$scope.totalPages = function(){
		$return = $scope.pageInfo.totalResults/$scope.pageInfo.resultsPerPage;
		if($scope.pageInfo.totalResults%$scope.pageInfo.resultsPerPage>0) {
			$return++;			
		}
		return $return;
	}
	
	//pagination ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	$scope.init();			/////////////////////////////////////////load the database values
	$scope.loadRatings(); //////////////////////////////////////load ratings
}

