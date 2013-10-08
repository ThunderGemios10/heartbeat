function settingsController($scope, $log, $modal, $routeParams, $http, youtubeService , sessionService, databaseService, $filter, $timeout) {
	$scope.rateName = "";
	$scope.rateDesc = "";
	$scope.ratingsData = [];
	$scope.uploadedData = [];
	$scope.uploadTable = [];
	$scope.projectId = 1;
	$scope.activateEnable = false;
	$scope.editEnable = false;
	$scope.deleteEnable = false;
	$scope.csv="";
	$scope.user = "";
	$scope.activeRow = {};
	$scope.editDescription = false;
	$scope.editFieldArr = [];
	$scope.statusOnly = true;
	$scope.showAdd = false;
	$scope.showEdit = false;
	$scope.showView = true;
	$scope.newTag = {};	
	$scope.newTag.intensity = [
		{level:1}
		,{level:2}
		,{level:3}
	];
	// $scope.tempRow = {};
	$scope.statusBtnLoading = false;
	$scope.tagType = [
		{tagTypeId:1,tagTypeName:'Primary'}	
		,{tagTypeId:3,tagTypeName:'Copyright'}
		,{tagTypeId:2,tagTypeName:'Optional'}
		,{tagTypeId:4,tagTypeName:'Language'}
	];
	$scope.clearField = function() {

	}
	sessionService.getCurrentUser().then(function(user){		
		$scope.user = user;
	});

	$scope.changeSelected = function(index,ratingRow) {		
		$scope.activeRow = ratingRow;		
		$scope.activeRow.idx = index;
		// $scope.scrollToTag(ratingRow.tagId);
		console.log($scope.activeRow);
		$scope.show('view');
	}
	$scope.scrollToTag = function(activeId) {
		// console.log($("#"+activeId).html());
		$('#scrollableDivForTags').animate({
	        scrollTop:   $("#"+activeId).offset().top - $('#scrollableDivForTags').offset().top + $('#scrollableDivForTags').scrollTop()
	    }, 1500);
	}
	$scope.loadRatings = function(activeId){
		databaseService.getAllTags().then(function(tags){
			$scope.ratingsData = tags;
			if(activeId) {
				$scope.resetViewTo(activeId);
				$timeout(function () {
		            $scope.scrollToTag(activeId)
			    }, 0);
			}
			else {
				$scope.resetView();
			}
		});
	}
	$scope.resetView = function(){
		var temp = $filter('orderBy')($scope.ratingsData,'name');
		temp = $filter('filter')(temp,$scope.searchTag);
		temp = $filter('filter')(temp,$scope.tagStatus());
		$scope.activeRow = temp[0];
	}
	$scope.resetViewTo = function(id){
		var temp = $filter('orderBy')($scope.ratingsData,'name');
		temp = $filter('filter')(temp,$scope.searchTag);
		temp = $filter('filter')(temp,$scope.tagStatus());
		var keepGoing = true;
		angular.forEach(temp,function(rating){
			if(keepGoing) {			
				if(rating.tagId == id.insertId) {
					$scope.activeRow = rating;
					$scope.scrollToTag($scope.activeRow.tagId);
					keepGoing = false;
				}
			}
		});		
	}
	$scope.convertToJSONDate = function(cdate) {
		var readableDate = new Date(cdate * 1000);
		var milliSecondDate = cdate * 1000;
		return milliSecondDate;
	}
	/***************************************************************/
	
	$scope.editField = function(rowArr,field) {
		$scope.resetField();
		rowArr[field] = true;
	}
	$scope.resetField = function() {
		angular.forEach($scope.ratingsData,function(rating){
			rating.editName = false;
			rating.editDescription = false;
		});
	}
	$scope.saveEditField = function(rating,field) {
		console.log('Saved');2
		rating[field] = false;
		$scope.resetField();
	}

	/***************************************************************/
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
	$scope.$watch('[searchTag, statusOnly]',function(){
		$scope.show("resetview");
	},true);
	$scope.tagStatus = function () {
		if($scope.statusOnly) {

			return {status:1};
		}
		return '';
	}
	$scope.saveTag = function(newTag) {
		databaseService.addSystemTag(newTag).then(function(response){
			// $scope.ratingsData.push(newTag);
			$scope.loadRatings(response.insertId);
			newTag.tagId = response.insertId;
			newTag.status = 1;
			$scope.activeRow = newTag;
			$scope.show('view');
			console.log(response);
		});
	}
	$scope.saveEditTag = function(editTag) {		
		databaseService.editSystemTag(editTag).then(function(response){
			// console.log(response);
			$scope.loadRatings(editTag.tagId);
			$scope.activeRow = editTag;
			$scope.show('view');
		});
	}
	$scope.deactivateTag = function(tagId) {		
		$scope.statusBtnLoading = true;
		databaseService.deactivateSystemTag(tagId).then(function(response){		
			$scope.activeRow.status=0;
			$scope.statusBtnLoading = false;
		});	
	}
	$scope.activateTag = function(tagId) {
		$scope.statusBtnLoading = true;
		databaseService.activateSystemTag(tagId).then(function(response){		
			$scope.activeRow.status=1;
			$scope.statusBtnLoading = false;
		});
	}

	$scope.resetPage = function(){
		$scope.currentPage = 1;
	};
	$scope.numPages = function () {
		return Math.ceil($scope.uploadTable.length / $scope.numPerPage);
	};
	$scope.getCSV = function(content, completed) {
		if(completed) {
			var newContent = [];
			var i=0;
			angular.forEach(content, function(item) {
				var newItem = {
					selected:true
					,item:item
				};

				newContent.push(newItem);
			});
			// console.log(newContent);
			$scope.uploadedData = newContent;
			// console.log(newContent);
			//pagination variables
			$scope.currentPage = 1;
			$scope.numPerPage = 10;
			$scope.maxSize = 5;	
		}
		else {
		   console.log("not yet");
		}
	};
	$scope.getParam = function(url,name)
	{
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec(url);
		if( results == null )
			return "";
		else
			return results[1];
	}
	$scope.save = function() {
		var uploadData = $scope.uploadedData
		console.log("Saving...");
		// console.log(uploadData);
		var videoIds = [];
		video.ids = [];
		video.info = [];
		angular.forEach(uploadData,function(data){
			if(data.selected){
				video.ids.push($scope.getParam(data.item[0],'v'));
				video.info.push(data);
			}
		});
		var size = uploadData.length;
		var loops = Math.ceil(size/50);
		var start = 0;
		var st = 0;
		var limit = 50;
		var ids = video.ids;
		var info = video.info;
		$scope.segment = [];
		$scope.detail = [];
		$scope.videoDetails = [];
		var trimmedIds = [];
		var trimmedInfos = [];
		$scope.progStatus = 0;
		for(idx=0;idx<loops;idx++) {
			start = idx * limit;			
			trimmedIds[idx] = ids.slice(start,start+limit);
			trimmedInfos[idx] = info.slice(start,start+limit);
			
			youtubeService.getDetails(trimmedIds[idx],idx).then(function(outDetails){
				if(outDetails) {
					details = outDetails[0];
					var count = 0;
					angular.forEach(details, function(detail){
						var valid = false;
						angular.forEach(trimmedInfos[outDetails.index], function(info){
							if(info.selected){
								if(detail.id==$scope.getParam(info.item[0],'v')) {
									detail.name = info.item[1];
									detail.email = "";
									detail.note = info.item[2];
									var ratingId = info.item[3];
									var rating = "";
									if(ratingId=="-1") {rating="copyright";}
									else if(ratingId=="?") {rating="questionable";}
									else if(ratingId=="0") {rating="unrelated";}
									else if(ratingId=="1") {rating="poor";}
									else if(ratingId=="2") {rating="average";}
									else if(ratingId=="3") {rating="good";}
									else if(ratingId=="4") {rating="great";}
									else if(ratingId=="5") {rating="pro";}
									else if(ratingId=="Approved") {rating="Approved";}
									else if(ratingId=="-1") {rating="";}
																		
									detail.ratings = rating;
									detail.dashboardInfo = {
										"videoId":detail.id
										,"in_playlist":info.item[3]
										,"Country_Code":info.item[4]
										,"Country_Name":info.item[5]
										,"Video_Offer":info.item[6]
										,"Video_Clicks":info.item[6]
									};
									valid=true;
									count++;									
								}
							}
						});	
						// console.log(count);
						// console.log(detail);
						// console.log(valid);
						if(valid){
							$scope.videoDetails.push(detail);
						}
					});
					$scope.segment.push(details);
				}
			});
		}
		
		// console.log(trimmedIds);
		$scope.$watch('videoDetails', function() {
			$scope.progStatus = ($scope.segment.length/loops)*100;			
			if($scope.progStatus==100) {				
				console.log("final Output:");	
				console.log($scope.videoDetails);
				var total = $scope.videoDetails.length;				
				var count = 0;
				databaseService.saveVideos($scope.videoDetails,false).then(function(data){
					console.log(data);
					if(data){
						count++;
						$scope.progStatus = data;						
					}
				});				
			}	
		}, true);
	};
	
	$scope.fadeAfterSelect = false;
	$scope.selectItem = function(select) {
		if(select) {			
			
			$('.fadeAfterSelect').fadeOut().queue(function() {
		        $scope.fadeAfterSelect = true;    
		    });
		}
	}
	$http({method: 'POST',url:'model/session_model.php',data: {userlevel:''},headers:{'Content-Type': 'application/data'}}).success(function(user,status,headers,config){		
		if(user=='false') changeLocation('#pageNotFound');
		else {
			$scope.loadRatings();							
		}
	}).error(function(data,status,headers,config){});
};

/*
	<pre><?php var_dump($_SESSION['channelId'])?></pre>
	<pre><?php var_dump($_SESSION["channelsResponse"])?></pre>
	<pre><?php var_dump($_SESSION["playlistId"])?></pre>
*/