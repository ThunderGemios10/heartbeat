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
	global $userId;
	global $collectionChannel;
	global $collectionAuthuser;
	global $useremail;
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
					$collectionAuthuser->save(array("user"=>$userinfo,"userId"=>array($useremail)));
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

function getLocalChannelById($cid) {	
	global $collectionChannel;
	global $userId;
	$convertedObj = array();
	$query = array('channelInfo.id' => $cid,"users"=>$userId);
	$cursor = $collectionChannel->find($query);
	if($cursor->getNext()) {
		foreach ($cursor as $doc) {
			$convertedObj = $doc;
		}
		return $convertedObj;
	}
	return array("err"=>"notFound","msg"=>"No channel with id = '".$cid."' found.");		
}

if(isset($request->saveUserChannel)){
	$values = $request->channel;
	echo json_encode(insertChannel($values),JSON_NUMERIC_CHECK);	
}
else if(isset($request->getUserChannel)){
	$cid = $request->channelId;
	echo json_encode(getLocalChannelById($cid),JSON_NUMERIC_CHECK);
}
else {
	

	// $collectionChannel->remove();
	$convertedObj = array();
	$cursor = $collectionChannel->find();
	if($cursor->getNext()) {
		foreach ($cursor as $doc) {
			array_push($convertedObj,$doc);
			echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
		}
	}
	echo "<hr/>";
	echo "<hr/>";
	echo "<hr/>";
	$convertedObj = array();
	$cursor = $collectionAuthuser->find();
	if($cursor->getNext()) {
		foreach ($cursor as $doc) {
			array_push($convertedObj,$doc);
			echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
		}
	}


}

?>