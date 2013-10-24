<?php session_start();?>
<!-- <div ng-hide="activeRow" class="center header"><img src="images/loading.gif"></img></div> -->
<!-- <div class="row content" ng-show="activeRow">	
	<div class="row pull-left">	 -->  	  
   <div sidebar-nav active="" col="2"></div>
   <div id="videoNode" class="row col-md-6" ng-init="show='about'">
		<div class="videoWrapper row">
			<!-- Copy & Pasted from YouTube -->			
			<iframe width="100%" height="100%" src="http://www.youtube.com/embed/{{activeRow.videoId}}?autoplay=0&modestbranding=1&rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>
		</div>
		<!-- http://www.youtube.com/embed/{{activeRow.videoId}}?autoplay=0&modestbranding=1&rel=0&wmode=transparent -->
	   <!-- </div>
	   <div id="videoNode" class="row col-md-6"> -->
	    
			<!--a><i class="glyphicon glyphicon-arrow-right"></i></a-->
			<!-- <div class="center" ng-hide="tags.length>0"><img ng-class="" ng-src="../images/small_loading.gif"></img></div> -->
		<div class="row boxed-left boxed-right boxed-bottom">				
			<div class="container">
				<h3 id="pageTitle">{{activeRow.videoInfo.snippet.title}}</h3>
				<div class="row" ng-init="hoverPrimary=false" ng-mouseover="hoverPrimary=true" ng-mouseleave="hoverPrimary=false">
					 <label class="btn col-md-1" ng-show="primaryPick&&hoverPrimary" ng-class="{true:'active',false:'false'}[primaryPick.tagId==primary.tagId]" popover="Remove rank" popover-trigger="mouseenter" popover-placement="right" ng-click="removeTag('primary');"><i class="glyphicon glyphicon-remove"></i>
						<input type="radio" ng-hide="true"><span ng-show="primary.tagId==5"></input>
					 </label>
					 <span class="col-md-1" ng-show="!primaryPick&&hoverPrimary">&nbsp;</span>
					<div class="btn-group cleanTagBox" ng-class="{'false':'col-md-offset-1'}[hoverPrimary]" data-toggle="buttons" ng-show="tags.length>0">
					  <label class="btn" title="{{primary.name}}" ng-repeat="primary in primaryTags | limitTo:3" ng-class="{true:'active',false:'false'}[primaryPick.tagId==primary.tagId]" ng-click="resetRateBar(primary)"><i class="glyphicon" ng-class="{1:'glyphicon-thumbs-up',2:'glyphicon-thumbs-down',3:'glyphicon-minus'}[primary.tagId]"></i>
						<input type="radio" ng-model="pick" ng-hide="true" value="{{primary.name}}"><span ng-show="primary.tagId==1">{{primary.name}}<span></input>
					  </label>
					  <label class="btn-padding">
						<a href="" ng-click="addToLater()" ng-hide="addedToLaterList">Ask me later</a>
						<span ng-show="addedToLaterList">Added to rank later list</span>
					  </label>
					</div>
					<div class="container">
						<div class="btn-group side-boxed pull-right boxed" data-toggle="buttons">
						  <label class="btn" ng-class="{true:'active',false:'false'}[show=='about']" ng-click="show='about'">
							<input type="radio" ng-model="pick" ng-hide="true"> About</input>
						  </label>
						  <label class="btn" ng-class="{true:'active',false:'false'}[show=='rank']" ng-click="show='rank'">
							<input type="radio" ng-model="pick" ng-hide="true"> Rank</input>
						  </label>							  
						</div>
					</div>				
				</div>	
			</div>			
		</div>
		<div class="row boxed-bottom boxed-left boxed-right" ng-show="show=='about'">
			<br/>
			<div class="container">
				<div divto-disqus="activeRow.videoInfo.snippet.title" ng-show="true"></div>				
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
		</div>
		<div ng-show="show=='rank'" class="row boxed-bottom boxed-left boxed-right no-shadow has-padding no-padding-top redHover">
			<div class="row col-md-12">
				<br ng-show="primaryPick"/>
				<div ng-show="primaryPick&&primaryPick.tagId!=3" class="container has-padding-sm">
					<div class="container col-md-3" ng-show="tags.length>0">
						<h5>How {{primaryPick.name}}<span ng-hide="primaryPick.prefix">?</span></h5>
					</div>
					<div class="col-md-9">
						<div class="clean-dropdown boxed">
					      <select class="clean-dropdown-select boxed" ng-model="primaryPick.selectedLevel" ng-options="i.level as i.defaultName for i in primaryPick.intensity">
					      	<option value="">Select...</option>
					      </select>
					    </div>
					</div>
				</div>
				<hr ng-show="primaryPick&&primaryPick.tagId!=3"/>
				<!-- <pre>{{secondaryPick|json}}</pre> -->
				<div class="container has-padding-sm" ng-init="hoverCopyright=false" ng-mouseover="hoverCopyright=true" ng-mouseleave="hoverCopyright=false">
					<div class="container col-md-3" ng-show="tags.length>0">
						<h5>Copyright </h5>
					</div>
					<div class="col-md-9">
						<div class="col-md-10">
							<div class="btn-group side-boxed" data-toggle="buttons">
							  <label class="btn" ng-repeat="secondary in secondtags" ng-class="{true:'active',false:'false'}[secondary.tagId==secondaryPick.tagId]" ng-click="selectSecondary(secondary)">
								<input type="radio" ng-model="pick" ng-hide="true"> {{secondary.name}}</input>
							  </label>							  
							</div>							
							<button type="button" popover="Remove rank" popover-trigger="mouseenter" popover-placement="right" ng-show="hoverCopyright&&secondaryPick" class="btn close pull-right" aria-hidden="true" ng-click="removeTag('copyright')">&times;</button>	  							
						</div>
						<div ng-show="secondaryPick&&secondaryPick.name!='Maybe'">
							<div class="clean-dropdown boxed">
						      <select class="clean-dropdown-select boxed" ng-model="secondaryPick.selectedLevel" ng-options="i.level as i.defaultName for i in secondaryPick.intensity">
						      	<option value="">Select...</option>
						      </select>
						    </div>
						</div>
					</div>
				</div>						
				<hr/>
				<div class="container has-padding-sm">
					<div class="container col-md-3">
						<h5>Language </h5>
					</div>
					<div class="col-md-9">
						<div class="">
							<div class="btn-group boxed">				
								<div class="inline"><select data-placeholder="&raquo;" multiple class="chzn-select languageTagBox" ng-model="language" ng-options="i as i.name for i in languageTags" chosen></select></div>
							</div>

						</div>
						<a href="" class="pull-right" ng-show="language.length>0" ng-click="showLanguageAdvanced=negate(showLanguageAdvanced)">Advanced</a>
					</div>	
					<div class="col-md-8 col-md-offset-4">
						<div class="container" ng-show="showLanguageAdvanced">
							<div ng-repeat="newLanguage in language" class="row">
								<div class="has-padding-sm col-md-5">
									{{newLanguage.name}}
								</div>
								<div class="col-md-5">
									<div class="clean-dropdown clean-dropdown-sm">
								      <select class="clean-dropdown-select clean-dropdown-sm" ng-model="newLanguage.selectedLevel" ng-options="i.level as i.defaultName for i in newLanguage.intensity">
								      	<option value="">Select...</option>
								      </select>
								    </div>
								</div>								
							</div>
						</div>						
					</div>
				</div>	
				<hr/>
				<div class="container has-padding-sm">
					<div class="container col-md-3">
						<h5>Category </h5>
					</div>					
					<div class="col-md-9">
						<div class="clean-dropdown" popover="Other category coming soon!" popover-trigger="mouseenter">
							<!-- <input type="text" class="clean-dropdown-select clean-dropdown-sm" value="Game" disabled></input> -->						
							<select class="clean-dropdown-select boxed">
								<option value="">Game</option>
							</select>
						</div>						
						<br/>
						<div>
							<div class="btn-group boxed">				
								<div class="inline"><select data-placeholder="&raquo;" multiple class="chzn-select chzn-custom-style languageTagBox" ng-model="game" ng-options="i as i.name for i in gameslist" chosen></select></div>
							</div>
						</div>
						<a href="" class="pull-right" ng-show="game.length>0" ng-click="showGameAdvanced=negate(showGameAdvanced)">Advanced</a>
					</div>
					<div class="col-md-8 col-md-offset-4">
						<div class="container" ng-show="showGameAdvanced">
							<div ng-repeat="newGame in game" class="row">
								<div class="has-padding-sm col-md-5">
									{{newGame.name}}
								</div>
								<div class="col-md-5">
									<div class="clean-dropdown clean-dropdown-sm">
								      <select class="clean-dropdown-select clean-dropdown-sm" ng-model="newGame.selectedLevel" ng-options="i.level as i.defaultName for i in newGame.intensity">
								      	<option value="">Select...</option>
								      </select>
								    </div>
								</div>								
							</div>
						</div>						
					</div>
				</div>
				<hr/>
				<div class="container has-padding-sm" ng-hide="true">
					<div class="container col-md-3">
						<h5>Tags </h5>
					</div>
					<div class="col-md-9">
						<div>
							<div class="btn-group boxed">				
								<div class="inline"><select data-placeholder="&raquo;" multiple class="chzn-select chzn-custom-style languageTagBox" ng-model="rating" ng-options="i as i.name for i in tags" chosen></select></div>
							</div>
						</div>
						<a href="" class="pull-right" ng-show="rating.length>0" ng-click="showFreeformTagsAdvanced=negate(showFreeformTagsAdvanced)">Advanced</a>
					</div>
					<div class="col-md-8 col-md-offset-4">
						<div class="container" ng-show="showFreeformTagsAdvanced">
							<div ng-repeat="newRank in rating" class="row">
								<div class="has-padding-sm col-md-5">
									{{newRank.name}}
								</div>
								<div class="col-md-5">
									<div class="clean-dropdown clean-dropdown-sm">
								      <select class="clean-dropdown-select clean-dropdown-sm" ng-model="newRank.selectedLevel" ng-options="i.level as i.defaultName for i in newRank.intensity">
								      	<option value="">Select...</option>
								      </select>
								    </div>
								</div>								
							</div>
						</div>						
					</div>
				</div>
				<!-- <hr/> -->
				<div class="container has-padding-sm">
					<div class="container col-md-3">
						<h5>Tags</h5>
					</div>
					<!-- {{free}} - {{freeformtags}} -->
					<div class="col-md-9">
						<div>
						    <input class="col-md-12 selectTagBox" ng-model="free" ng-data="freeformtags" placeholder="&raquo;"></input>
						</div>						
					</div>					
				</div>
				<!-- <hr/> -->
			</div>						
			<span class="label pull-right" ng-class="{'label-success':saveStatus=='Saved!','label-danger':saveStatus=='Saving...'}">{{saveStatus}}</span>
			<!-- <hr/>
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
			<hr/> -->
			
		</div>
		<br/>
		<div id="disqus_thread"></div>
	</div>
	<div id="listNode" class="col-md-4 .has-indent">		
		<div ng-show="suggestRated">		
			<div class="container sidebar-videos" ng-repeat="video in suggestRated">
				<div class="col-md-4 no-padding">						
					<a title="Play/Rate Video!" href="#!/play/{{video.videoId}}"><img class="" ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="130"></img></a>
				</div>
				<div class="col-md-7">
					<div class="ellipsis"><div>
						<p><a title="{{video.videoInfo.snippet.title}}" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></p>	
					</div></div>						
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
