function playController($scope, $timeout, $location, $rootScope, $filter, sessionService, $routeParams, $http, databaseService, youtubeService, languageService, sessionService, DataService,$filter,$resource) {
	
	databaseService.getAllTags("json").then(function(result){		
		// console.log(result);
		$scope.primaryRank = result;
	});

	$scope.freeformtagss = [
		"Dota"
		,"Dota 2"
		,"League of Legends"
		,"Heroes of the Storm"
		,"Smite"		
	];
	$scope.find = function (item) {
		console.log(item);
	};
	$scope.change = function (val) {
		console.log(val);
	};
	$scope.rankShowLevel1 = false;
	$scope.rankShowLevel2 = false;
	$scope.rankShowLevel3 = false;
	$scope.rankShowLevel4 = false;

	$scope.ytLinkVideo = DataService.ytLink_video;
	$scope.ytLinkChannel = DataService.ytLink_channel;
	$scope.ytLink_user = DataService.ytLink_user;
	$rootScope.page = "play";
	$scope.categoryGood = [];
	$scope.categoryBad = [];
	$scope.newRate = [];
	$scope.newRate.pick = 0;	
	$scope.activeRow = [];
	$scope.rank = [];
	$scope.tagsBox = [];
	$scope.categories = [];
	$scope.saveText = 'Save?';
	$scope.ratePerTagArr = [];
	$scope.rate = 3;
	$scope.max = 5;
	$scope.isReadonly = false;
	$scope.defaultIntensity = 3;
	$scope.showMoreText = 'Show more';
	$scope.savingStatus = 1;
	$scope.tagReady1 = false;
	$scope.tagReady2 = false;
	$scope.page = $rootScope.page;
	// $scope.rating = [];
	// $scope.primaryPick = {};	
	$scope.hoveringOver = function(value) {
		$scope.overStar = value;
		$scope.percent = 100 * (value / $scope.max);
	};
	
	$scope.resetRateBar = function(selected) {		
		$scope.primaryIntensity = selected;
		$scope.primaryPick = selected;
		$scope.showPrimaryIntensity = true;
	}
	$scope.selectSecondary = function(selectedLevel){
		$scope.secondaryPick = selectedLevel;
	}
	$scope.selectedSecondaryIntensity = function(selected,prefix){
		$scope.secondaryPick.prefix = prefix;
		$scope.secondaryPick.selectedLevel = selected;
	}
	$scope.negate = function(negateVar) {
		negateVar = !negateVar;
		return negateVar;
	}
	$scope.dropdownChange = function (selectedLevel,intensities) {		
		angular.forEach(intensities,function(intensity) {
			// console.log(intensity.level+"=="+selectedLevel);
			if(intensity.level==selectedLevel) {
				// prefix = intensity.prefix;
				return intensity.prefix;
			}
		});
		// console.log(prefix);
	}
	$scope.defaultPick = function (argument) {
		
	}
	$scope.removeTag = function(what) {
		if(what=="primary") {
			$scope.primaryPick = null;
		}
		if(what=="primaryIntensity") {
			$scope.primaryPick.selectedLevel = false;
		}
		else if(what=="copyright"){
			$scope.secondaryPick = null;
		}
		else if(what=="language"){
			$scope.language = null;
		}
		else if(what=="tags"){
			$scope.rating = null;
		}
		else if(what=="game"){
			$scope.game = null;
		}
	}
	sessionService.getCurrentUser().then(function(user){
		if(user) {
			$scope.username = user.name;
			$scope.useremail = user.email;
			$scope.guest = false;		
		}
		else {
			$scope.guest = true;
		}
	});	
	$scope.$watch("userVidTags",function(){
		if($scope.tagDetails) {			
			angular.forEach($scope.tagDetails,function(tagDt){
				if(tagDt.rate) {
					$scope.rating.push(tagDt.rate);	
				}				
			});
			$scope.newRate.pick = $scope.tagDetails[0].rate;
		}
	});
	$scope.showMoreFunc = function() {
		// console.log($scope.showMore);
		if($scope.showMore) {
			$scope.showMoreText="Show more";
			$scope.showMore=false;
		}
		else {
			$scope.showMoreText="Show less";
			$scope.showMore=true;
		}
	}
	$scope.saveRating = function() {		
		var videoId = $scope.activeRow.videoId;

		var primaryPick = $scope.primaryPick;
		var secondaryPick = $scope.secondaryPick;
		
		var rating = $scope.rating;			
		var language = $scope.language;
		var game = $scope.game;		
		
		temp = [];
		if(primaryPick) temp.push(primaryPick);
		if(secondaryPick) temp.push(secondaryPick);
		if(rating) angular.forEach(rating,function(tmp){
			temp.push(tmp);
		});
		if(language) angular.forEach(language,function(tmp){
			temp.push(tmp);
		});
		if(game) angular.forEach(game,function(tmp){
			temp.push(tmp);
		});

		free = $scope.free;

		console.log(free);
		$http({method: 'POST',url:'model/videotags_model.php',data: {vidId:videoId,videoTags:temp,mode:1,free:free},headers:{'Content-Type': 'application/data'}}).success(function(data,status,headers,config){		
			$scope.saveText = 'Saved!'
			$scope.ratingSaved = true;
			$scope.checkClass = "success-text";
			$scope.saveStatus = "Saved!";
			$scope.savingStatus = 1;
			console.log(data);
		}).error(function(data,status,headers,config){});	
	};
	// console.log('$routeParams.videoId');
	// console.log($routeParams.videoId);
	databaseService.getVideo($routeParams.videoId).then(function(result){
		databaseService.getAllTags(1).then(function(tags){
			$scope.primaryTags = tags;
			// $scope.rating = [];
			databaseService.getAllTags(2).then(function(tags){
				$scope.tags = tags;
				databaseService.getCurrentUserFreeTags().then(function(tags){
					$scope.freeformtags = tags;
					console.log('getCurrentUserFreeTags');
					console.log(tags);
					databaseService.getAllTags(3).then(function(tags){
						$scope.secondtags = tags;			
						databaseService.getAllTags(4).then(function(tags){
							// console.log('.getAllTags(4)');
							// console.log(tags);
							tags.splice(0, 1);
							var topLanguages = [];
							var topLanguagesList = [
								'English'
								,'Chinese'
								,'German'							
							];
							angular.forEach(topLanguagesList,function(list){
								for(var i=0;i<tags.length;i++) {
									// console.log(tags[i]);								
									if(tags[i].name==list) {
										topLanguages.push(tags[i]);
										tags.splice(i, 1);
									}										
								}
							});
							var orderedLanguage = topLanguages;
							angular.forEach(tags,function(tag){
								orderedLanguage.push(tag);
							});
							$scope.languageTags = orderedLanguage;
							databaseService.getAllTags(5).then(function(tags){
								tags.splice(0, 1);
								$scope.gameslist = tags;
								$scope.loadVidTags();
							});						
						});
					});	
				});				
			});
		});
		// console.log('result.length<1');
		// console.log(result);
		$scope.activeRow = result;
		if(result.length<1) {
			youtubeService.getDetails($routeParams.videoId,0).then(function(outDetails){
				console.log('getDetails');
				console.log(outDetails);
				outDetails = outDetails[0];
				outDetails.rating = "";				
				databaseService.saveVideos(outDetails,false).then(function(result){										
					console.log('saveVideos');console.log(result);
					databaseService.saveReferenceVideo(outDetails,'anyTV').then(function(data){
						console.log('saveReferenceVideo');console.log(data);
					});
					databaseService.getVideo($routeParams.videoId).then(function(result){						
						console.log('getVideo');console.log(result);
						$scope.activeRow = result;
					});
				});				
			});
		}
	});
	databaseService.getRatedVideo(20).then(function(video){
		// console.log('getRatedVideo');console.log(video);
		$scope.suggestRated = video;
	});
	databaseService.getUnRatedVideo(5).then(function(video){		
		$scope.suggestUnRated = video;
	});
	$scope.loadVidTags = function(){
		databaseService.getVideoTags($scope.activeRow.videoId,1).then(function(vidtags){		
			$scope.userPrimaryTags = vidtags[0];
			if($scope.userPrimaryTags) {
				var keepGoing = true;
				angular.forEach($scope.primaryTags,function(prim){
					if(keepGoing){
						if(prim["tagId"]==$scope.userPrimaryTags["tagId"]) {
							prim.selectedLevel = $scope.userPrimaryTags["tagLevel"];								
							prim.prefix = $scope.userPrimaryTags["prefix"];
							$scope.primaryPick = prim;
							keepGoing = false;
						}
					}
				});	
			}
			$scope.tagReady1 = true;
			databaseService.getCurrentVideoUserFreeTags($scope.activeRow.videoId).then(function(vidtags){	
				$scope.free = vidtags;
			databaseService.getVideoTags($scope.activeRow.videoId,2).then(function(vidtags){				
				$scope.userVidTags = vidtags;
				$scope.rating = [];	
				angular.forEach($scope.userVidTags,function(vidTags){
					var keepGoing = true;
					angular.forEach($scope.tags,function(vt){
						if(keepGoing){	
							console.log(vt["tagId"]+"======"+vidTags["tagId"]);											
							if(vt["tagId"]==vidTags["tagId"]) {
								var keepGoing2 = true;
								vt.selectedLevel = null;
								vt.prefix="";
								angular.forEach(vt.intensity,function(intensity){
									if(keepGoing2){
										// console.log(intensity);
										// console.log('2'+intensity.level+" == "+vidTags["tagLevel"]);
										if(intensity.level==vidTags["tagLevel"]) {
											vt.selectedLevel = vidTags["tagLevel"];
											// console.log(intensity.defaultName);
											vt.prefix = intensity.defaultName;											
												
											keepGoing2 = false;
											$scope.savingStatus = 1;
										}
									}
								});	
								$scope.rating.push(vt);											
							}
						}	
					});
				});				
				$scope.tagReady2 = true;
				databaseService.getVideoTags($scope.activeRow.videoId,3).then(function(vidtags){
					// console.log('type level 3');
					// console.log(vidtags);
					$scope.userSecondaryTags = vidtags[0];
					if($scope.userSecondaryTags) {
						var keepGoing = true;
						angular.forEach($scope.secondtags,function(sec){
							if(keepGoing){
								// console.log('$scope.activeRow.videoId,3');
								if(sec["tagId"]==$scope.userSecondaryTags["tagId"]) {
									sec.selectedLevel = $scope.userSecondaryTags["tagLevel"];								
									sec.prefix = $scope.userSecondaryTags["prefix"];
									$scope.secondaryPick = sec;
									keepGoing = false;							
								}
							}
						});	
					}
					$scope.tagReady3 = true;
					databaseService.getVideoTags($scope.activeRow.videoId,4).then(function(vidtags){
						console.log('$scope.activeRow.videoId,4');
						console.log(vidtags);
						$scope.language = [];
						$scope.userVidLanguageTag = vidtags;
						// $scope.language = vidtags;
						angular.forEach($scope.userVidLanguageTag,function(vidTags){
							var keepGoing = true;
							angular.forEach($scope.languageTags,function(vt){
								if(keepGoing){							
									// console.log('1'+vt["tagId"]+" == "+vidTags["tagId"]);
									if(vt["tagId"]==vidTags["tagId"]) {
										var keepGoing2 = true;
										vt.selectedLevel=null;
										vt.prefix="";
										angular.forEach(vt.intensity,function(intensity){
											if(keepGoing2){
												// console.log(intensity);
												// console.log('2'+intensity.level+" == "+vidTags["tagLevel"]);
												if(intensity.level==vidTags["tagLevel"]) {
													vt.selectedLevel = vidTags["tagLevel"];
													// console.log(intensity.defaultName);
													vt.prefix = intensity.defaultName;													
													keepGoing = false;
													keepGoing2 = false;
													$scope.savingStatus = 1;
												}
											}
										});
										$scope.language.push(vt);														
									}
								}	
							});
						});	
						$scope.tagReady4 = true;
						databaseService.getVideoTags($scope.activeRow.videoId,5).then(function(vidtags){
							// console.log('$scope.activeRow.videoId,5');
							// console.log(vidtags);
							$scope.game = [];
							$scope.userVidGameTag = vidtags;
							// $scope.language = vidtags;
							angular.forEach($scope.userVidGameTag,function(vidTags){
								var keepGoing = true;
								angular.forEach($scope.gameslist,function(vt){
									if(keepGoing){							
										// console.log('1'+vt["tagId"]+" == "+vidTags["tagId"]);
										if(vt["tagId"]==vidTags["tagId"]) {
											var keepGoing2 = true;
											vt.selectedLevel=null;
											vt.prefix="";
											angular.forEach(vt.intensity,function(intensity){
												if(keepGoing2){
													// console.log(intensity);
													// console.log('2'+intensity.level+" == "+vidTags["tagLevel"]);
													if(intensity.level==vidTags["tagLevel"]) {
														vt.selectedLevel = vidTags["tagLevel"];
														// console.log(intensity.defaultName);
														vt.prefix = intensity.defaultName;
														
														keepGoing = false;
														keepGoing2 = false;
														$scope.savingStatus = 1;
													}
												}
											});	
											$scope.game.push(vt);													
										}
									}	
								});
							});	
							$scope.tagReady5 = true;
						});
					});
				});
			});
			});		
		});
		
		databaseService.getVideoTagCount($scope.activeRow.videoId).then(function(tagCount){
			$scope.tagCounts = [];
			// console.log($scope.activeRow.videoId);
			angular.forEach(tagCount,function(count){
				var keepGoing=true;
				angular.forEach($scope.primaryTags,function(tag){
					if(keepGoing){
						if(tag.tagId==count["tagId"]) {
							count.tagName = tag.name;							
							angular.forEach(tag.intensity,function(intensity){							
								if(intensity.level == count.tagLevel)
									count.tagLevelName = intensity.defaultName;
							});
							count.tagFullName = count.tagLevelName+" "+count.tagName							
							keepGoing = false;
							$scope.tagCounts.push(count);
						}
					}							
				});
				angular.forEach($scope.tags,function(tag){
					if(keepGoing){
						if(tag["tagId"]==count["tagId"]) {
							count.tagName = tag["name"];
							angular.forEach(tag.intensity,function(intensity){
								if(intensity.level == count.tagLevel)
								count.tagLevelName = intensity.defaultName;
							});
							count.tagFullName = count.tagLevelName+" "+count.tagName
							keepGoing = false;
							$scope.tagCounts.push(count);
						}
					}							
				});
			});			
		});
			
	}
	/**************************** AUTO SAVE *******************************************/	
	var timer = false;    
    $scope.autoSave = function() {
    	$scope.savingStatus = 0;    	
    	$scope.saveStatus = "Saving...";
    	if (timer) {
            $timeout.cancel(timer);
        }
        timer = $timeout(function () {
            $scope.saveRating();
        }, 1500);
    }
    var count = 0;
    $scope.$watch('[primaryPick, rating, secondaryPick, language, game, free, tagReady1, tagReady2, tagReady3, tagReady4, tagReady5]',function(){
		// console.log($scope.tagReady1+" --> "+$scope.tagReady2+" --> "+$scope.tagReady3+" --> "+$scope.tagReady4);
		if($scope.tagReady1 && $scope.tagReady2 && $scope.tagReady3 && $scope.tagReady4 && $scope.tagReady5) {
			if(count == 0) {
				count = 1;
			}
			else {
				$scope.autoSave();
			}
		}
	},true);

	window.onbeforeunload = function (event) {
	  if($scope.savingStatus==0){
		  var message = "You're rankings are not yet saved.";
		  if (typeof event == 'undefined') {
		    event = window.event;
		  }
		  if (event) {
		    event.returnValue = message;
		  }
		  return message;
	  }
	}
}