
<?php
	// session_start();
	if(isset($_SESSION["userlevel"])) {
		if($_SESSION["userlevel"]!='admin') {
			//header("location: error.php");
		}		
	}
	else{
		//header("location: error.php");
	}
?>
<div class="container">
	<div class="col-md-12" ng-controller="settingsController">	
			<div class="row inline">
				<form ng-upload action="model/file_upload.php" class="col-md-5 inline">						
					<h4>Upload Data:</h4> 
												
						<input id="upload" type="file" accept = "csv/*" required name="upload"></input>						
					
					<!-- <span class="col-md-4" id="fileName"></span> -->
					<div id="uploadMetaDiv" class="col-md-5 row-fluid" style="display:none;">
						<input type="submit" value="Upload" upload-submit="getCSV(content, completed)" ng-show="uploadedData.length<=0"/>
						<a href="" ng-show="uploadedData.length>0" ng-click="save()"><i class="glyph-icon glyph-icon-hdd"></i> Save to db</a>
					</div>						
                </form>
                <div class="inline col-md-5 pull-right">
					<input type="text" class="col-md-4 form-control input-sm" value="Search" placeholder="Search" ng-model="uploadSearch" ng-change="resetPage()"/>
				</div>
			</div>	
		<!-- <pre>{{uploadedData | json}}</pre> -->
		<span ng-show="uploadedData.length>0">No. of rows: <span class="label label-success">{{uploadedData.length | number}} - {{videoDetails.length}}</span></span>
		<table class="table table-hover">
			<tr ng-repeat="uploadRows in paginated = (uploadTable = (unPaganated = (uploadedData | filter:uploadSearch))) | paginate:currentPage:numPerPage">
				<td>
					<label class="checkbox inline" ng-init="currentIndex=($index+1)+((currentPage-1)*10)">
					   <input type="checkbox" id="inlineCheckbox1" value="option1" ng-model="uploadedData[currentIndex-1].selected"> {{currentIndex}}
					</label>
				</td>
				<td ng-repeat="upload in uploadRows.item  track by $index">
					{{upload}}
				</td>
				<!-- <td>
					<pre>{{uploadRows.item | json}}</pre>
				</td> -->
			</tr>
		</table>
		<!-- <div class="row has-border remove-padding">
			<div class="col-md-12" ng-show="filteredUploadedData.length==0">No Data.</div>
			<div class="row has-border-row" >
			  <div class="col-md-1" ng-init="currentIndex=($index+1)+((currentPage-1)*10)">					
				<label class="checkbox inline">
				   <input type="checkbox" id="inlineCheckbox1" value="option1" ng-model="uploadedData[currentIndex-1].selected"> {{currentIndex}}
				</label>
			  </div>
			  <div class="col-md-3 uploadViewCell hideOverflow" ng-repeat="upload in uploadRows.item">
				  <div title="{{upload}}">{{upload}}</div>
			  </div>			
			</div>			
		</div> -->
		<div class="progress" ng-class="{true:'progress-striped'}[progStatus<100]" ng-show="videoDetails.length>0" progress-bar="progStatus"></div>
		<div class="pull-right pointer-cursor" data-pagination="" data-num-pages="numPages()" 
		  data-current-page="currentPage" data-max-size="maxSize"  
		  data-boundary-links="true"></div>				
		<pagination 
			total-items="paginated.length" 
			page="currentPage" 
			max-size="maxSize" 
			class="pagination-small" 
			boundary-links="true">
		</pagination>
	</div>
	
</div>
<script>
	// This will parse a delimited string into an array of
    // arrays. The default delimiter is the comma, but this
    // can be overriden in the second argument.
    $("input#upload").change(function () {
        if($(this).val()=="") {	
			//none-selected
			$("#fileSelect").removeClass('btn-success').addClass('btn-primary');
			// $("#uploadMetaDiv").css("display","none");
		}
		else {
			//file selected
			$("#uploadMetaDiv").css("display","block");
			var filename = $(this).val().toString().replace(/^.*[\\\/]/, '');
			$("#fileName").html(filename);
			$("#fileSelect").removeClass('btn-primary').addClass('btn-success');
		}
    });
	function getParam( name )
	{
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if( results == null )
			return "";
		else
			return results[1];
	}

	var frank_param = getParam( 'pid' );
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