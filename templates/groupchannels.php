<?php session_start();?>
	<div sidebar-nav active="anyTV" col="2"></div>
	<div class="col-md-10 pull-left boxed-left has-padding-sm" ng-show="showNewGroup"></div>	
	<div class="col-md-8 pull-left boxed-left">
		<div class="col-md-12 pull-left boxed no-padding">
			<table class="table table-striped no-margin">
				<thead>
					<tr>
					  <th colspan="4" class="no-padding-bottom">
						<!-- <span class="pull-left"><h4>Groups</h4></span>
						<div class="pull-right col-lg-11">
							<div class="pull-right col-lg-9">
								<button type="button" class="btn btn-default" popover="Add a group" popover-trigger="mouseenter" popover-placement="right" ng-click="show()">
								    <span class="glyphicon glyphicon-plus"></span>
								</button>
								<div class="col-lg-10">
								    <div class="input-group">
								      <input type="text" class="form-control" ng-model="keyword.groupId">
								      <div class="input-group-btn">
								        <button type="button" class="btn btn-default"> <span class="glyphicon glyphicon-search"></span></button>							        
								      </div>
								    </div>
								</div>
							</div>
						</div>	 -->	
						<div class="row">
							<div class="no-border-radius whiteHover groupBanner" style="background-image: url(uploads/groupBanner/{{groupInfo.bannerLink}}); background-size:cover; background-position:center center;"></div>
						    <div class="container no-padding-vertical">
							     <h2>{{groupInfo.groupAltName}}</h2>
							     <p>{{groupInfo.groupDescription}}</p>
						    </div>
						</div>
						
						<div class="btn-group side-boxed pull-left boxed" data-toggle="buttons" ng-init="showTab='channels'">
						  <label class="btn" ng-class="{true:'active',false:'false'}[showTab=='channels']" ng-click="changeTab('channels')">
							<input type="radio" ng-model="pick" ng-hide="true"> Channels</input>
						  </label>	
						  <label class="btn" ng-class="{true:'active',false:'false'}[showTab=='videos']" ng-click="changeTab('videos')">
							<input type="radio" ng-model="pick" ng-hide="true"> Videos</input>
						  </label>					 
						</div>

						<div class="pull-right col-md-4">
							<input type="text" ng-show="showTab=='channels'" class="form-control" ng-model="keyword" placeholder="Search channels"></input>
							<input type="text" ng-show="showTab=='videos'" class="form-control" ng-model="keyword" placeholder="Search videos"></input>
						</div>
					  </th>
					</tr>
				</thead>
				<!-- <pre>{{channelList.channelList | json}}</pre> -->
				<tbody>
					<tr>
						<td ng-init="showAddChannel=true" ng-show="manageMode">
							<div class="col-md-12 pull-left has-padding-sm">
								<!-- <form action="" ng-submit="add(newgroup)" class="col-md-12"> -->
									<table class="table table-bordered">
										<thead>
											<tr>
											  <th colspan="3"><i class="glyphicon glyphicon-plus"></i> Add channel
											  	<button type="button" class="pull-right close" aria-hidden="true" ng-click="hide()">&times;</button>
											  </th>
											</tr>
										</thead>
										<tbody ng-init="addNetworkPick='username'">
											<tr ng-click="addNetworkPick='username'">
												<td class="col-md-1" ng-class="{'hovered-grey':addNetworkPick=='username'}">							  		
													<span class="pull-right">
														<input type="radio" name="addNetworkPick" value="username" ng-model="addNetworkPick"></input>
													</span>
											 	</td>
											  	<td class="col-md-1">							  		
													<span class="pull-right">
														Channel Username
													</span>
											 	</td>
											 	<td class="col-md-3 no-padding">
													<input type="text" name="name" ng-model="channelUsername" class="form-control no-border clean-form-control" placeholder="anyTVNetwork">
											 	</td>
											</tr>
											<tr ng-click="addNetworkPick='id'">
												<td class="col-md-1" ng-class="{'hovered-grey':addNetworkPick=='id'}">							  		
													<span class="pull-right">
														<input type="radio" name="addNetworkPick" value="id" ng-model="addNetworkPick"></input>
													</span>
											 	</td>
											  	<td class="col-md-4">							  		
													<span class="pull-right">
														Channel Id
													</span>
											 	</td>
											 	<td class="col-md-7 no-padding" ng-class="{'highlight-red':exist}">
													<input type="text" name="id" ng-model="channelId" class="form-control no-border clean-form-control" placeholder="UCLEwyS6chjwToWeXwzbjlBA">
											 	</td>
											</tr>											
											<tr ng-click="addNetworkPick='csv'">
												<td class="col-md-1" ng-class="{'hovered-grey':addNetworkPick=='csv'}">							  		
													<span class="pull-right">
														<input type="radio" name="addNetworkPick" value="csv" ng-model="addNetworkPick"></input>
													</span>
											 	</td>
											  	<td class="col-md-1">
													<span class="pull-right">CSV</span>
											 	</td>
											 	<td class="col-md-3">
													<!-- <select type="password" name="type" ng-model="newgroup.grouptype" required ng-options="type.id as type.name for type in groupType" class="form-control no-border clean-form-control">
												    </select>	 -->													  
												    <!-- <span class="label label-success">Format:</span> &nbsp;<span>network, channel id</span> -->
												    <form ng-upload action="model/file_upload.php">
												    	<input id="upload" type="file" accept = "csv/*" required name="upload"></input>	
												    	<input type="submit" value="Upload" upload-submit="getCSV(content, completed)"/>
												    </form>
											 	</td>
											</tr>
										</tbody>
									</table>		
									<div class="alert alert-danger" ng-show="exist">Oh wait! Group Id is already taken.</div>
									<div class="pull-right">
										<!-- <button type="button" class="btn btn-default no-border-radius" ng-click="save('resetview')">Close</button> -->
							       		<button type="button" ng-click="add()" class="btn btn-primary no-border-radius">Add Channel/s</button>									
										
						       		</div>
						   		<!-- </form>		 -->
							</div>
						</td>
					</tr>
					<tr>
						<!-- <pre>{{channelList.channelList | json}}</pre> -->
					  	<td ng-show="showTab=='channels'" ng-init="channel.hovered=false">
					  		<div class="col-md-3 has-padding-vertical" ng-repeat="channel in (channelList.channelList | filter:keyword) | orderBy:'id':true">
					  			<img ng-src="{{channel.channelInfo.snippet.thumbnails.high.url}}" width="200" height="200"></img>
					  			<span class="bold hideOverflow">
									{{channel.channelInfo.snippet.title}}
								</span>
								<!-- <pre>{{channel | json}}</pre> -->
					  		</div>		

					 	</td>
					 	<td ng-show="showTab=='videos'" ng-init="video.hovered=false">
					  		<div class="col-md-3 has-padding-vertical" ng-repeat="video in videoList | filter:keyword | orderBy:'id':true" ng-show="video.videoId">
					  			<img ng-src="{{video.videoInfo.snippet.thumbnails.medium.url}}" width="160" height="150"></img>
								<div class="ellipsis">
									<div>
										<p><bold><a title="{{video.videoInfo.snippet.title}}" href="#!/play/{{video.videoId}}">{{video.videoInfo.snippet.title}}</a></bold></p>	
									</div>
								</div>
					  		</div>		

					 	</td>
					 	<!-- <pre>{{videoList | json}}</pre> -->
					  	<!-- <td class="col-md-3">							  		
							<span class="bold">
								{{channel.channelInfo.snippet.title}}
							</span>
					 	</td>
					 	<td class="col-md-5">
							<span class="color-grey-2">
								{{getGroupTypeById(channel.groupType)}}
							</span>
					 	</td>
					 	<td class="col-md-2"> -->
					 		<!-- <a href="" type="button" class="btn btn-default" popover="Add channel" popover-trigger="mouseenter" popover-placement="right">
							    <span class="glyphicon glyphicon-plus"></span>
							</a> -->
							<!-- <span class="glyphicon glyphicon-trash pointer-cursor" ng-click=""></span> -->
					 	<!-- </td> -->
					</tr>					
				</tbody>
			</table>
		</div>

		<!-- <pre>{{channelList.channelList | json}}</pre> -->

  <!-- <input type="text" ng-model="id"> -->
  
  <!-- <input type="file" ng-file-select="onFileSelect($files)" multiple> -->
  <!-- <div class="drop-box" ng-file-drop="onFileSelect($files);" ng-show="ddSupported">drop files here</div> -->
  <!-- <div ng-file-drop-available="dropSupported=true" ng-show="!ddSupported">HTML5 Drop File is not supported!</div> -->


	</div>

<script>
// var frank_param = getParam( 'pid' );
	function CSVToArray( strData, strDelimiter ){
    	// Check to see if the delimiter is defined. If not,
    	// then default to comma.
    	strDelimiter = (strDelimiter || ",");

    	// Create a regular expression to parse the CSV values.
    	var objPattern = new RegExp(
    		(
    			// Delimiters.
    			"(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

    			// Quoted fields.
    			"(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

    			// Standard fields.
    			"([^\"\\" + strDelimiter + "\\r\\n]*))"
    		),
    		"gi"
    		);


    	// Create an array to hold our data. Give the array
    	// a default empty first row.
    	var arrData = [[]];

    	// Create an array to hold our individual pattern
    	// matching groups.
    	var arrMatches = null;


    	// Keep looping over the regular expression matches
    	// until we can no longer find a match.
    	while (arrMatches = objPattern.exec( strData )){

    		// Get the delimiter that was found.
    		var strMatchedDelimiter = arrMatches[ 1 ];

    		// Check to see if the given delimiter has a length
    		// (is not the start of string) and if it matches
    		// field delimiter. If id does not, then we know
    		// that this delimiter is a row delimiter.
    		if (
    			strMatchedDelimiter.length &&
    			(strMatchedDelimiter != strDelimiter)
    			){

    			// Since we have reached a new row of data,
    			// add an empty row to our data array.
    			arrData.push( [] );

    		}


    		// Now that we have our delimiter out of the way,
    		// let's check to see which kind of value we
    		// captured (quoted or unquoted).
    		if (arrMatches[ 2 ]){

    			// We found a quoted value. When we capture
    			// this value, unescape any double quotes.
    			var strMatchedValue = arrMatches[ 2 ].replace(
    				new RegExp( "\"\"", "g" ),
    				"\""
    				);

    		} else {

    			// We found a non-quoted value.
    			var strMatchedValue = arrMatches[ 3 ];

    		}


    		// Now that we have our value string, let's add
    		// it to the data array.
    		arrData[ arrData.length - 1 ].push( strMatchedValue );
    	}

    	// Return the parsed data.
    	return( arrData );
    }
	$(document).ready(function() {
		$('#thefile').change(function(e) {
			if (e.target.files != undefined) {
				var reader = new FileReader();
				
				reader.onload = function(e) {
					// $('#text').text(e.target.result);
					var convertedCSV = CSVToArray(e.target.result,",");
					document.getElementById("length").innerHTML = convertedCSV.length;
				};
				reader.readAsText(e.target.files.item(0));
			}
			return false;
		});
	});
</script>