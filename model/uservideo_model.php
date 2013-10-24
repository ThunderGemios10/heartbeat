<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];


function getRankedVideoByUser() {
	global $useremail;
	$returnArr = array();
	$tempRow = array();
	$query = '
		SELECT * FROM `tbl_videoperuser`
			WHERE useremail = "'.$useremail.'"
			ORDER BY `tbl_videoperuser`.`dateModified`  DESC
	';
	$result = mysql_query($query);

    while($row = mysql_fetch_assoc($result)){
		$tempRow["videoId"] = $row["videoId"];
		$tempRow["dateModified"] = $row["dateModified"];
		array_push($returnArr, $tempRow);       
	}     
	return $returnArr; 
}
function getRankedVideoForFeed() {
	global $useremail;
	$returnArr = array();
	$tempRow = array();
	$query = '
		SELECT * FROM `tbl_videoperuser`			
			ORDER BY `tbl_videoperuser`.`dateModified` DESC
			LIMIT 0,30
	';
	$result = mysql_query($query);

    while($row = mysql_fetch_assoc($result)){
		$tempRow["videoId"] = $row["videoId"];
		$tempRow["dateModified"] = $row["dateModified"];
		$tempRow["useremail"] = $row["useremail"];
		array_push($returnArr, $tempRow);       
	}     
	// echo $query;
	return $returnArr; 
}
function addRankLater($value='') {
	global $useremail;
	$query = '
		INSERT INTO `tbl_ranklater` (
		   id
		  ,`videoId`
		  ,`user`
		  ,`dateCreated`
		  ,`dateModified`	
		) VALUES
		  (
		  	NULL
		    ,"'.$value.'"
		    ,"'.$useremail.'"
		    ,NOW()
		    ,NOW()		    
		  )
	';
	$result = mysql_query($query);
	return $result; 
}
function isAddedToLater($value='') {
	global $useremail;
	$query = '
		SELECT status FROM `tbl_ranklater`
			WHERE videoId = "'.$value.'"
				AND user = "'.$useremail.'"
	';
	$result = mysql_query($query);
	// var_dump($result);
	$row = mysql_fetch_assoc($result);
	if($row){		
		// var_dump($row);
		return $row["status"]; 
	}
	else {
		return false; 
	}
	
}
function getRankLaterByUser($value='') {
	global $useremail;
	$query = '
		SELECT RL.*,VPU.`id` FROM `tbl_ranklater` RL
			LEFT JOIN `tbl_videoperuser` VPU 
				ON RL.`videoId` = VPU.`videoId`
					AND RL.`user` = VPU.`useremail`
			WHERE user = "'.$useremail.'"			
	';
	// echo $query;
	$result = mysql_query($query);
	$returnArr = array();
	$return = array();
	$contents = array();
	while($row = mysql_fetch_assoc($result)) {
		$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/video_mongomodel.php?vid='.$row["videoId"]);
		$returnArr["videoInfo"] = json_decode($contents);
		$returnArr["dateModified"] = $row["dateModified"];
		$returnArr["status"] = $row["status"];
		$returnArr["ranked"] = isset($row["id"])?true:false;
		array_push($return, $returnArr);
	}
	return $return;
}

if(isset($request->getRanked)) {	
	echo json_encode(getRankedVideoByUser());
}
else if(isset($request->getRankedAll)) {	
	echo json_encode(getRankedVideoForFeed());
}
else if(isset($request->addToLater)) {
	$value = $request->addToLater;
	echo json_encode(addRankLater($value));
}
else if(isset($request->isAddedToLater)) {
	$value = $request->isAddedToLater;
	echo json_encode(array("status"=>isAddedToLater($value)));
}
else if(isset($request->getRankLaterByUser)) {
	echo json_encode(getRankLaterByUser(),JSON_NUMERIC_CHECK);
}
mysql_error();
?>