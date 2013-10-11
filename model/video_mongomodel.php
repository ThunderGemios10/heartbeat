<?php  
session_start();
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

$collectionVideo = new MongoCollection($db, 'videos');
function getLocalVideoById($vid) {	
	global $collectionVideo;
	$convertedObj = array();
	if(is_string($vid)){
		$query = array('videoId' => $vid);
		$cursor = $collectionVideo->find($query);
		foreach ($cursor as $doc) {
			$convertedObj = $doc;
		}
	}
	return array_reverse($convertedObj);
}
function insertVideoReference($values,$gid) {	
	$saved = true;
	foreach ($values as $value) {
		$result = mysql_query('
			INSERT INTO `tbl_videoreference` (`videoId`,`groupId`)
				VALUES (\''.$value->id.'\',\''.$gid.'\')
		');
		if($result<1) {
			$saved = false;
		}		
		echo '
				INSERT INTO `tbl_videoreference` (`videoId`,`groupId`)
					VALUES (\''.$value->id.'\',\''.$gid.'\')
			';
		
	}
	// echo $error;
	return $saved;
}
if(isset($_GET["vid"])){
	$vid = $_GET["vid"];
	echo json_encode(getLocalVideoById($vid),JSON_NUMERIC_CHECK);
}
else if(isset($request->insert)){
	$values =$request->values;	
	$gid =$request->groupId;	
	echo json_encode(insertVideoReference($values,$gid),JSON_NUMERIC_CHECK);
}
?>