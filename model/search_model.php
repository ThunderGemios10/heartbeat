<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];
$collectionVideo = new MongoCollection($db, 'videos');

function searchVideo($keyword) {
	global $collectionVideo;
	$regexObj = new MongoRegex("/.*".$keyword."*./i"); 
	$cursor = $collectionVideo->find(array('$or'=> array(
			array("videoInfo.snippet.title"=>$regexObj)
			,array("videoInfo.snippet.description"=>$regexObj)
			,array("videoInfo.snippet.channelTitle"=>$regexObj)
		)
	));
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);			
	}	
	return $convertedObj;
}

if(isset($request->searchVideo)) {
	$keyword = $request->keyword;
	echo json_encode(searchVideo($keyword),JSON_NUMERIC_CHECK);
}


mysql_error();
?>