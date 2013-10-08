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
if(isset($request->getRanked)) {	
	echo json_encode(getRankedVideoByUser());
}
else if(isset($request->getRankedAll)) {	
	echo json_encode(getRankedVideoForFeed());
}

mysql_error();
?>