<?php session_start();?>
<div ng-hide="activeRow" class="center header"><img src="images/loading.gif"></img></div>
<!-- <div class="row content" ng-show="activeRow">	
	<div class="row pull-left">	 -->  	  
	   <div sidebar-nav active="newsfeed" col="2"></div>
	   <div id="videoNode" class="row col-md-6">
		<div class="videoWrapper row">
			<!-- Copy & Pasted from YouTube -->			
			<iframe width="100%" height="100%" src="http://www.youtube.com/embed/{{activeRow.videoId}}?autoplay=0&modestbranding=1&rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>
		</div>

	    <div class="row boxed no-shadow has-padding redHover" ng-init="newRow.rating=[];">
			<!--a><i class="glyphicon glyphicon-arrow-right"></i></a-->
			<div class="center" ng-hide="tags.length>0"><img ng-class="" ng-src="../images/small_loading.gif"></img></div>			
			<br/>
			<div class="row">
				<div class="btn-group cleanTagBox col-md-4" data-toggle="buttons" ng-show="tags.length>0">					  
				  <label class="btn" ng-show="primaryPick" title="Remove Rank" ng-class="{true:'active',false:'false'}[primaryPick.tagId==primary.tagId]" ng-click="removeTag('primary');"><i class="glyphicon glyphicon-remove"></i>
					<input type="radio" ng-hide="true"><span ng-show="primary.tagId==5"></input>
				  </label>
				  <label class="btn" title="{{primary.name}}" ng-repeat="primary in primaryTags | limitTo:3" ng-class="{true:'active',false:'false'}[primaryPick.tagId==primary.tagId]" ng-click="resetRateBar(primary);"><i class="glyphicon" ng-class="{1:'glyphicon-thumbs-up',2:'glyphicon-thumbs-down',3:'glyphicon-question-sign'}[primary.tagId]"></i>
					<input type="radio" ng-model="pick" ng-hide="true" value="{{primary.name}}"><span ng-show="primary.tagId==5">{{primary.name}}<span></input>
				  </label>
				</div>
				<div ng-show="primaryPick" class="col-md-3 cleanTagBox has-padding-sm">
					<span class=""><span ng-hide="primaryPick.prefix">How</span><span ng-show="primaryPick.prefix">{{primaryPick.prefix}}</span> {{primaryPick.name}}<span ng-hide="primaryPick.prefix">?</span> &raquo;</span>	
				</div>
				<div class="no-padding side-boxed col-md-4" ng-show="primaryPick">							
					<div class="col-md-12 boxed no-shadow pull-left">
						<div class="btn-group" data-toggle="buttons">						  
						  <label class="btn" ng-repeat="intensities in primaryPick.intensity" ng-class="{true:'active',false:'false'}[primaryPick.selectedLevel==intensities.level]" ng-click="primaryPick.prefix=intensities.defaultName;primaryPick.selectedLevel=intensities.level">
							<input type="radio" ng-hide="true"> {{intensities.defaultName}}</input>
						  </label>
						</div>	
					</div>
				</div>
			</div>
			<br/>
			<table class="table table-condensed col-md-6" ng-init="max=5;isReadonly=false;">
				<tr class="container boxed no-shadow">
					<td class="has-padding-sm">
						<i class="glyphicon glyphicon-remove pull-left" ng-show="secondaryPick" ng-click="removeTag('copyright')"></i>					
					</td>
					<td class="col-md-1">
						<div class="container has-padding-sm has-padding-left-1" ng-show="tags.length>0">
							Copyright
						</div>
					</td>
					<td class="col-md-3 no-padding">
						<div class="btn-group side-boxed" data-toggle="buttons">
						  <label class="btn" ng-repeat="secondary in secondtags" ng-class="{true:'active',false:'false'}[secondary.tagId==secondaryPick.tagId]" ng-click="selectSecondary(secondary)">
							<input type="radio" ng-model="pick" ng-hide="true"> {{secondary.name}}</input>
						  </label> 			  
						</div>
					</td>
					<td class="col-md-7 no-padding">
						<div class="btn-group side-boxed" data-toggle="buttons">
						  <label class="btn" ng-repeat="secondaryIntensity in secondaryPick.intensity" ng-class="{true:'active',false:'false'}[secondaryIntensity.level==secondaryPick.selectedLevel]" ng-click="selectedSecondaryIntensity(secondaryIntensity.level,secondaryIntensity.defaultName)">
							<input type="radio" ng-model="pick" ng-hide="true"> {{secondaryIntensity.defaultName}}</input>
						  </label> 			  
						</div>	
					</td>					
				</tr>				
			</table>
			<table class="table no-margin table-condensed col-md-6" ng-init="max=5;isReadonly=false;">
				<tr class="container boxed no-shadow">
					<td class="col-md-1 has-padding-sm">
						<i class="glyphicon glyphicon-remove pull-left" ng-show="language" ng-click="removeTag('language')"></i>
					</td>
					<td class="col-md-3">
						<div class="container has-padding-sm" ng-show="tags.length>0">
							Language
						</div>
					</td>
					<td class="col-md-8 no-padding">
						<div class="btn-group">				
							<div class="inline"><select data-placeholder="&raquo;" multiple class="chzn-select chzn-custom-style languageTagBox" ng-model="language" ng-options="i as i.name for i in languageTags" chosen></select></div>
						</div>
					</td>
				</tr>
			</table>
			<table class="table table-condensed col-md-6" ng-init="max=5;isReadonly=false;">						
				<tr ng-repeat="newLanguage in language" class="container boxed no-shadow">
					<td class="col-md-6">
						<div ng-show="tags.length>0" class="has-padding-sm">
							<a href="">{{newLanguage.prefix}} {{newLanguage.name}}</a>
						</div>
					</td>
					<td class="col-md-6 no-padding">
						<div class="btn-group side-boxed" data-toggle="buttons">
						  <label class="btn" ng-repeat="intensities in newLanguage.intensity" ng-class="{true:'active',false:'false'}[newLanguage.selectedLevel==intensities.level]" ng-click="newLanguage.prefix=intensities.defaultName;newLanguage.selectedLevel=intensities.level">
							<input type="radio" ng-model="pick" ng-hide="true"> {{intensities.defaultName}}</input>
						  </label> 			  
						</div>	
					</td>
				</tr>
			</table>
			<!-- <pre>{{language | json}}</pre> -->
			<table class="table no-margin table-condensed col-md-6" ng-init="max=5;isReadonly=false;">
				<tr class="container boxed no-shadow  no-margin">
					<td class="col-md-1 has-padding-sm">
						<i class="glyphicon glyphicon-remove pull-left" ng-show="rating" ng-click="removeTag('tags')"></i>
					</td>
					<td class="col-md-4">
						<div class="container has-padding-sm" ng-show="tags.length>0">
							Tags
						</div>
					</td>
					<td class="col-md-7 no-padding">
						<div class="btn-group">				
							<div class="inline"><select data-placeholder="&raquo;" multiple class="chzn-select chzn-custom-style otherTagBox" ng-model="rating" ng-options="i as i.name for i in tags" chosen></select></div>
						</div>
					</td>					
				</tr>
			</table>
			<table class="table table-condensed col-md-6" ng-init="max=5;isReadonly=false;">						
				<tr ng-repeat="newRank in rating" class="container boxed no-shadow">
					<td class="col-md-6">
						<div ng-show="tags.length>0" class="has-padding-sm">
							<a href="">{{newRank.prefix}} {{newRank.name}}</a>
						</div>
					</td>
					<td class="col-md-6 no-padding">
						<div class="btn-group side-boxed" data-toggle="buttons">
						  <label class="btn" ng-repeat="intensities in newRank.intensity" ng-class="{true:'active',false:'false'}[newRank.selectedLevel==intensities.level]" ng-click="newRank.prefix=intensities.defaultName;newRank.selectedLevel=intensities.level">
							<input type="radio" ng-model="pick" ng-hide="true"> {{intensities.defaultName}}</input>
						  </label> 			  
						</div>	
					</td>
				</tr>
			</table>		
			<span class="label pull-right" ng-class="{'label-success':saveStatus=='Saved!','label-danger':saveStatus=='Saving...'}">{{saveStatus}}</span>			
			<div class="inline">
				<h4 class="tagBoxed inline">Top tags: &nbsp;&nbsp;<span class="label label-danger" ng-hide="tagCounts">Not ranked yet, rank this video by clicking Good, Bad or Undecided.</span></h4>
				<span class="boxed tagBoxed" ng-repeat="topTags in (tagCounts | orderBy : 'tagCount' : true) | limitTo: 3">
					{{topTags.tagFullName}}
				</span>
			</div>		
			<hr/>
			<div class="container">
				<div divto-disqus="activeRow.videoInfo.snippet.title" ng-show="true"></div>
				<a><h3 id="pageTitle">{{activeRow.videoInfo.snippet.title}}</h3></a>
				<ul class="metaInfo">
					<li>by: <a href="{{ytLink_user}}{{activeRow.videoInfo.snippet.channelTitle}}" target="_blank" class="linkSmallBlack"><span class="label label-success">{{activeRow.videoInfo.snippet.channelTitle}}</span></a></li>
					<li>&bull; <span ng-class="{snippet.publishedAt:'label label-warning'}[currentSort.sorttext]">{{activeRow.videoInfo.snippet.publishedAt | date:'MMM d yyyy'}}</span></li>
					<li>&bull; <span ng-class="{statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{activeRow.videoInfo.statistics.viewCount | number}} Views</span></li>										
				</ul>
				<p>
					<span ng-hide="showMore" class="hideOverflow descShort">{{activeRow.videoInfo.snippet.description}}</span>
					<pre class="cleanPre" ng-show="showMore">{{activeRow.videoInfo.snippet.description}}</pre>
					<a href="" ng-click="showMoreFunc()">{{showMoreText}}</a>
				</p>					
			</div>
			<hr/>
			<div id="disqus_thread"></div>
		</div>						
	  </div>

	  <div id="listNode" class="col-md-4 .has-indent">		
			<div ng-show="suggestRated">
				<!--h5>Rated Videos</h5-->			
				<div class="container sidebar-videos" ng-repeat="video in suggestRated">
					<div class="col-md-4">						
						<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="130"></img></a>
					</div>
					<div class="col-md-8">
						<div class="ellipsis"><div>
							<p><a title="{{video.videoInfo.snippet.title}}" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></p>	
						</div></div>
						<!--strong class="sidebar-video-title"><a title="{{video.videoInfo.snippet.title}}" class="hideOverflow" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></strong-->
						<span class="show-onhover pull-right">														
							<a class="btn btn-small accordion-toggle" data-toggle="collapse" data-parent="#accordion_detailedview" href="#collapse_{{$index}}"><i class="icon-chevron-right"></i> </a>
						</span>						
						<small>
							<ul class="metaInfo">
								<li>									
									<a href="{{ytLinkChannel}}{{video.videoInfo.snippet.channelId}}" target="_blank" class="">{{video.videoInfo.snippet.channelTitle}}</a>																	
								</li>
							</ul>
							<ul class="metaInfo">								
								<li><span ng-class="{video.videoInfo.statistics.viewCount:'label label-warning'}[currentSort.sorttext]">{{video.videoInfo.statistics.viewCount | number}} Views</span></li>										
							</ul>
							<p ng-hide="true"><span class="hideOverflow descShort">{{video.videoInfo.snippet.description}}</span></p>
						</small>
					</div>
				</div>
				<hr/>
			</div>
		</div>		
	 <!--   </div>
    </div>   -->
	<script type="text/javascript">  
		/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
			 // required: replace example with your forum shortname
		
		var disqus_shortname = 'yanktest';
		var disqus_identifier = 'newidentifier';
		var disqus_url = 'http://any.tv/yank_test';
		var disqus_developer = '1';
		var title = "";
		
		var disqus_config = function () {
		  this.language = "en";	
		  this.callbacks.onReady = [function() { loadDisqus(title); }];
		  // console.log(this.callbacks);
		};
		var loadDisqus = function (pass) {
			title = pass;
			if(window.DISQUS) {
				// console.log("pass");
				// console.log(pass);
				reset(pass);
			}
			else {
				(function() {
				// /* * * DON'T EDIT BELOW THIS LINE * * */
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq)
				})();
			}
		}
		var angularLoaded = function(val) {
			// console.log(val);
			loadDisqus(val);
		}
		
	</script>
	<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
	<!-- <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a> -->
</div>
<script type="text/javascript">
	var reset = function(info) {
		// console.log("info");
		// console.log(info);
		var afterHash = window.location.hash;
		var hashArr = afterHash.split("/");
		var vidid = hashArr[2];
		// console.log(hashArr);
		// console.log(vidid);
		DISQUS.reset({
		  reload: true,
		  config: function () { 
			this.page.identifier = 'video_'+vidid;
			this.page.url = "http://any.tv/yank_test/home.php"+afterHash;
			this.page.title = info +" - heartbeat.tm";
			this.language = "en";
			this.callbacks.onReady = [];
			// console.log('Final value of title: ');
			// console.log(this.page.title);
		  }
		});
		
	};
	// loadDisqus(pass);	

</script>
