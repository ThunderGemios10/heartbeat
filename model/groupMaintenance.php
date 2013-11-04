<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];


function createGroup($value='') {
	global $useremail;
	$query = '
		INSERT INTO 
			`tbl_group`(
				`id`
				,`groupAltName`
				,`groupId`
				,`groupDescription`
				,`bannerLink`
				,`dateCreated`
				,`dateModified`
				,`creator`
				,`groupType`
			) VALUES (
				NULL
				,"'.$value->name.'"
				,"'.$value->id.'"
				,"'.$value->description.'"
				,""
				,NOW()
				,NOW()
				,"'.$useremail.'"
				,"'.$value->grouptype.'"
			); 
	';	
	$result = mysql_query($query);
	return array("response"=>$result);
	
}
function addChannelToGroup($channelId='',$groupId='') {
	global $useremail;

	$query = '
		INSERT INTO 
			`tbl_channelreference`(
				`id`
				,`channelId`
				,`groupId`
				,`dateCreated`
				,`addedBy`
			) VALUES (
				NULL
				,"'.$channelId.'"
				,"'.$groupId.'"
				,NOW()
				,"'.$useremail.'"
			); 
		
	';	
	if($channelId!=''&&$channelId!=NULL) {
		$result = mysql_query($query);	
		return array("response"=>$result);
	} 
	else {
		return array("response"=>"ChannelId is empty");
	}		
}
if(isset($request->newGroup)) { echo json_encode(createGroup($request->newGroup));}
else if(isset($request->newChannel)) { echo json_encode(addChannelToGroup($request->channelId,$request->groupId));}
mysql_error();
?>