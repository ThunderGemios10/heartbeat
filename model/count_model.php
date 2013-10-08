<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];

function modifyVideoTag($videoId,$videoTags){
	global $useremail;	
}
function getVideoTagCount($videoId) {
	global $useremail;
	$returnArr = array();
	$tempRow = array();
	$query = '
		SELECT videoId
			,tagId
			,tagLevel
			,count(*) tagCount
		FROM `tbl_videoperuser` VPU
			INNER JOIN `tbl_tagspervideo` TPV
				ON VPU.id = TPV.videoperuserId
		GROUP BY videoId, tagId, tagLevel
		HAVING videoId = "'.$videoId.'"
	';
	// echo $query;
	$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result)){		
		$tempRow["videoId"] = $row["videoId"];					
		$tempRow["tagId"] = $row["tagId"];
		$tempRow["tagLevel"] = $row["tagLevel"];
		$tempRow["tagCount"] = $row["tagCount"];
		array_push($returnArr, $tempRow);	
	}
	// var_dump($returnArr);
	return $returnArr;
}

if(isset($request->getVideoTagCount)) {
	$videoId = $request->vid;
	echo json_encode(getVideoTagCount($videoId));	
}
else {

}

mysql_error();
?>