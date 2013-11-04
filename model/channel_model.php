<?php  
// Config  
session_start();
// $dbhost = 'localhost';  
$dbname = 'heartbeatdb';  
require 'connection.php';


$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

$collectionChannel = new MongoCollection($db, 'channel');
$collectionAuthuser = new MongoCollection($db, 'authuser');


if(isset($_SESSION["userId"])) {
	$userId = $_SESSION["userId"]->{'$id'};
	$useremail = $_SESSION["userinfo"]["email"];
	$userinfo = $_SESSION["userinfo"];
}
// $collectionVideo->remove();
function insertChannel($inserts) {
	ini_set('max_execution_time', 300);
	global $userinfo;
	global $userId;
	global $collectionChannel;
	global $collectionAuthuser;
	global $useremail;
	// var_dump($inserts);
	// $id = $inserts->id;
	if(isset($userId)) {
			$cursor = $collectionChannel->find(array("channelInfo.id"=>$inserts->id));
			if($cursor->getNext()){
				$collectionChannel->update(
					array(
						'channelInfo.id'=>$inserts->id		
					)
					,array(
						'$addToSet'=>array(
							'users'=>$useremail
						)
					)
				);
			}
			else {
				try {
					// $collectionAuthuser->save(array("user"=>$userinfo,"userId"=>array($useremail)));
					$collectionChannel->save(array("channelInfo"=>$inserts,"users"=>array($useremail)));	
				} catch(MongoCursorException $e) {
				    return array('err'=>'saveFailed','msg'=>$e);
				}
				return true;
			}		
	}
	else {
		return 'epic';
	}	
}

function insertVerifiedChannel($inserts) {
	ini_set('max_execution_time', 300);
	global $userinfo;
	global $userId;
	global $collectionChannel;
	global $collectionAuthuser;
	global $useremail;
	// var_dump($inserts);
	// $id = $inserts->id;
	if(isset($userId)) {
			$cursor = $collectionChannel->find(array("channelInfo.id"=>$inserts->id));
			if($cursor->getNext()){
				$collectionChannel->update(
					array(
						'channelInfo.id'=>$inserts->id
					)
					,array(
						'$addToSet'=>array(
							'users'=>$useremail
						)
					)
				);
			}
			else {
				try {
					// $collectionAuthuser->save(array("user"=>$userinfo,"userId"=>array($useremail)));
					$collectionChannel->save(array("channelInfo"=>$inserts,"users"=>array($useremail)));	
				} catch(MongoCursorException $e) {
				    return array('err'=>'saveFailed','msg'=>$e);
				}
				return true;
			}		
	}
	else {
		return 'epic';
	}	
	
}
function getLocalChannelById($cid,$userId) {	
	global $collectionChannel;
	// return $userId;
	$convertedObj = array();
	if($userId=="") {
		$query = array('channelInfo.id' => $cid);			
	}
	else {
		// return "nice";
		$query = array('channelInfo.id' => $cid,"users"=>$userId);	
	}
	$cursor = $collectionChannel->find($query);
	if($cursor->getNext()) {
		foreach ($cursor as $doc) {
			$convertedObj = $doc;
		}
		return $convertedObj;
	}
	return array("err"=>"notFound","msg"=>"No channel with id = '".$cid."' found .");		
}
function getChannelByGroupId($value='') {
	$returnArr = array();
	$tempRow = array();
	$channelList = array();
	if($value=="anyTV") {
		$query = '
			SELECT * 
				FROM `tbl_channelreference`		
				LIMIT 0,52
		';
	}
	else {
		$query = '
			SELECT * 
				FROM `tbl_channelreference`
				WHERE groupId = "'.$value.'"
				LIMIT 0,52
		';
	}
	
	// echo $query;
	$result = mysql_query($query);
	$i=0;
	// var_dump($result);
	while($row = mysql_fetch_assoc($result)){
		// $tempRow["channelId"] = $row["channelId"];
		$localChannel = getLocalChannelById($row["channelId"],"");
		// echo '111112322332323';
		// var_dump($localChannel->err);
		if(isset($localChannel['err'])) {
			$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/../apirequest/youtube-api-channel.php?channelId='.$row["channelId"]);
			// var_dump($contents);
			$jsonresult = json_decode($contents);
			if(isset($jsonresult->items[0])) {
				$localChannel = $jsonresult->items[0];
				// var_dump($localChannel['items'][0]);
				insertChannel($localChannel,"");
				$localChannel = getLocalChannelById($row["channelId"],"");	
			}			
		}
		$localChannel["id"] = $row["id"];
		array_push($channelList,$localChannel);
		if($i==0) {
			$returnArr["groupId"] = $row["groupId"];
			$returnArr["dateCreated"] = $row["dateCreated"];
		}
			
		// array_push($channelList, $tempRow);
		$i++;
	}
	$returnArr["channelList"] = $channelList;
	return $returnArr;	
}
function getChannelIdByChannelUsername($value='') {
	$returnArr = array();
	$result = mysql_query($query);	
	while($row = mysql_fetch_assoc($result)){	
		$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/../apirequest/youtube-api-channel.php?channelId='.$row["channelId"]);	
	}
	return $returnArr;	
}
if(isset($request->saveUserChannel)){
	$values = $request->channel;
	echo json_encode(insertChannel($values),JSON_NUMERIC_CHECK);	
}
else if(isset($request->getUserChannel)){
	$cid = $request->channelId;
	echo json_encode(getLocalChannelById($cid,$userId),JSON_NUMERIC_CHECK);
}
else if(isset($request->getChannelByGroupId)){
	$gid = $request->groupId;
	echo json_encode(getChannelByGroupId($gid),JSON_NUMERIC_CHECK);
}
else if(isset($request->getusername)){
	$gid = $request->getusername;
	echo getChannelIdByChannelUsername($gid);
}
else if(isset($_GET["parseByGroupId"])) {
	$query = '
		SELECT Network, `Channel` channelId, `From`, Videos
			FROM mergedreport_csv 
			WHERE Network LIKE "%'.$_GET["parseByGroupId"].'%"
				AND `From` = "active"
	';

	
	$arrayOfVideos = array();
	$arrayOfVideos["groupId"] = $_GET["parseByGroupId"];
	$arrayOfVideos["videos"] = array();
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){
		$rowArrId = array();
		if(!is_null($row["Videos"])) {
			$rowArrId = explode(" ",$row["Videos"]);
		}
		foreach ($rowArrId as $temp) {
			if(!is_null($temp)&&$temp!="")
				array_push($arrayOfVideos["videos"],$temp);
		}
	}
	
	if(isset($_GET["beautify"])) {
		echo $query;
		echo "<hr/>";
		echo "<pre>". print_r($arrayOfVideos["videos"],true)."</pre>";
	}
	else {
		echo json_encode($arrayOfVideos["videos"],JSON_NUMERIC_CHECK);
	}

	if(isset($_GET["setGroupId"])) {
		echo '<hr/>Set group id by: '.$_GET["setGroupId"];
		$query = '
			INSERT 
				INTO tbl_videoreference(id,videoId,groupId) VALUES
				
		';
		$i=0;
		foreach ($arrayOfVideos["videos"] as $value) {
			if($i==0) {
				$query .= '							
					(NULL,"'.$value.'","'.$_GET["setGroupId"].'")
				';	
			}
			else {
				$query .= '							
					,(NULL,"'.$value.'","'.$_GET["setGroupId"].'")
				';	
			}				
			$i++;			
		}
		$query .= '							
					ON DUPLICATE KEY UPDATE videoId=videoId;
			';
		echo "<hr/>".$query;
		$result = mysql_query($query);
		var_dump($result);
		if($result){

			echo '<hr/>SUCCESS!';
		}
		mysql_error();
	}
	// $contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/../apirequest/youtube-api-video.php?videoId='.$row["videoId"]);
	// $jsonresult = json_decode($contents);
	// if(isset($jsonresult->id)) {
	// 	$video = $jsonresult;
	// 	insertVideo($video);

	// }			
				// array_push($arrayOfVideos,$video);
	
	// $returnArr["channelList"] = $channelList;
	// return $returnArr;	

}
else {
	// 
	// $collectionChannel->remove(array('_id' => new MongoId('527201d7fc30e5800e000000')));
	// $collectionChannel->remove();
	// echo "collectionChannel";
	// echo "<hr/>";
	// $convertedObj = array();
	// $cursor = $collectionChannel->find();
	// if($cursor->getNext()) {
	// 	foreach ($cursor as $doc) {
	// 		array_push($convertedObj,$doc);
	// 		echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
	// 	}
	// }
	// echo "<hr/>";
	// echo "collectionAuthuser";
	// echo "<hr/>";
	// $collectionAuthuser->remove(array('_id' => new MongoId('5257abe4fc30e5f80d000000')));
	// $convertedObj = array();
	// $cursor = $collectionAuthuser->find();
	// if($cursor->getNext()) {
	// 	foreach ($cursor as $doc) {
	// 		array_push($convertedObj,$doc);
	// 		echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
	// 	}
	// }


}

?>