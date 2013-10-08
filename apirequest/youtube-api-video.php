<?php
//this program accepts an array of youtubURLS as an input.
//It outputs information about the videos and channel info
//Format:JSON 

ini_set('max_execution_time', 3000);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata); //convert the JSON to php object
//$ids = $request->ids;  //$ids is now an array of IDs


function queryVideos($ids){
	require 'include/oauth_instance.php';
	$csv_ids = generateCsv($ids);
	$searchResponse = $youtube->videos->listVideos('id,snippet,statistics',
		array('id' => $csv_ids,
			'fields' => 'items(id,snippet(title,description,publishedAt,channelId,channelTitle,thumbnails(medium)),statistics)'
	));
	return $searchResponse["items"];
}

if(isset($request->ids)) {
	$ids = $request->ids;
	try {		
		require 'arrToCSV.php';
		//get vid/listid from the URL provided in the $idList array
		// $index = 0;
		// foreach($ids as $i)
		// {
			// if($i != "")
			// {
				// $parts = parse_url($i);
				// parse_str($parts['query'],$query);
				
				// $queryId[$index] = $query['v'];
				//$queryList[$index] = $query['list'];
				// $index++;
			// }
		// }
		// $csv_ids = generateCsv($queryId);
		$searchFinalResponse['items']=array();
		if(is_array($ids)) {			
			$loops = sizeof($ids)/50;
			$start = 0;
			$limit = 50;
			// $temporary = array();
			for($i=0;$i<$loops;$i++)
			{
				$st = $i * 50;
				$trimmed = array_slice($ids, $st,$limit);
				$temporary[$i] = queryVideos($trimmed);
			}
		}
		else {
			$single = array($ids);
			$temporary[0] = queryVideos($single);			
		}
		array_push($searchFinalResponse,$temporary[0]);
		for($i=1;$i<sizeof($temporary);$i++)
		{
			foreach($temporary[$i] as $eachData) {
				array_push($searchFinalResponse,$eachData);
			}
		}
		
			
		
		
		echo json_encode($searchFinalResponse,JSON_NUMERIC_CHECK);
		// echo "<pre>";
		// print_r($searchResponse);
		// echo "</pre>";
	  } 
	  catch (Google_ServiceException $e) {
	   echo htmlspecialchars($e->getMessage());
	  } 
	  catch (Google_Exception $e) {
	   echo htmlspecialchars($e->getMessage());
	  }
}
else if (isset($request->query) && isset($request->maxResults)) {
	$q = $request->query;
	$max = $request->maxResults;
	$pageToken = $request->pageToken;
	
  // Call set_include_path() as needed to point to your client library.
	try {
	require 'include/oauth_instance.php';
	// if(false);
	if($pageToken!=""||$pageToken!=null) {
		$searchResponse = $youtube->search->listSearch('id,snippet', array(
			'q' => $q
			,'type' => 'video'
			,'maxResults' => $max
			,'pageToken' => $pageToken
			,'fields' => 'items(id,snippet(title,publishedAt,description,thumbnails,channelTitle)),prevPageToken,nextPageToken,pageInfo'
		));
	}
	else {
		$searchResponse = $youtube->search->listSearch('id,snippet', array(
			'q' => $q
			,'type' => 'video'
			,'maxResults' => $max	
			,'fields' => 'items(id,snippet(title,publishedAt,description,thumbnails,channelTitle)),prevPageToken,nextPageToken,pageInfo'
		));
	}
	echo json_encode(($searchResponse));
	
	} catch (Google_ServiceException $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
		htmlspecialchars($e->getMessage()));
	} catch (Google_Exception $e) {
		$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
		htmlspecialchars($e->getMessage()));
	}
}
else if(isset($request->feed)) {
	
}

?>
