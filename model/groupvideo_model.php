<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];

function getGroupVideo($groupId,$filter,$start,$limit) {
	$returnArr = array();
	$return = array();
	$tempRow = array();
	$query = '
		SELECT groupId
				,videoId
				,id
				,tagLevel
				,name
				,defaultName
				,dateModified
				,goods
				,score
				,SUM(score) sumOfScore
			FROM (SELECT 
				VRF.groupId
				,VRF.videoId
				,T.id
				,tagLevel
				,name
				,defaultName
				,VPU.dateModified
				,count(*) goods
				,count(*) * tagLevel as score		
						FROM tbl_videoperuser VPU 
							INNER JOIN tbl_tagspervideo TPV 
								ON VPU.id = TPV.videoperuserId 
							INNER JOIN tbl_tags T 
								ON T.id = TPV.tagId
							INNER JOIN tbl_intensityPerTag IPT 
								ON IPT.tagId = T.id
							INNER JOIN tbl_videoreference VRF
								ON VPU.videoId = VRF.videoId
						WHERE T.id = 1
						AND IPT.level = TPV.tagLevel
						AND groupId = "'.$groupId.'"
						GROUP BY VRF.videoId,T.id,tagLevel
						ORDER BY goods DESC, videoId
		     ) perTagLevelView
			GROUP BY
				videoId
			ORDER BY sumOfScore DESC
			LIMIT '.$start.', '.$limit.'
	';
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){		
		$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/video_mongomodel.php?vid='.$row["videoId"]);
		// var_dump($contents);
		$tempRow["videoInfo"] = json_decode($contents);
		$tempRow["videoId"] = $row["videoId"];
		$tempRow["groupId"] = $row["groupId"];
		$tempRow["sumOfScore"] = $row["sumOfScore"];		
		array_push($returnArr, $tempRow);
	}
	$maxcount_query = '
		SELECT count(*) maxcount FROM (
			SELECT groupId
				,videoId
				,id
				,tagLevel
				,name
				,defaultName
				,dateModified
				,goods
				,score
				,SUM(score) sumOfScore
				,count(videoId) maxcount
			FROM (SELECT 
				VRF.groupId
				,VRF.videoId
				,T.id
				,tagLevel
				,name
				,defaultName
				,VPU.dateModified
				,count(*) goods
				,count(*) * tagLevel as score		
						FROM tbl_videoperuser VPU 
							INNER JOIN tbl_tagspervideo TPV 
								ON VPU.id = TPV.videoperuserId 
							INNER JOIN tbl_tags T 
								ON T.id = TPV.tagId
							INNER JOIN tbl_intensityPerTag IPT 
								ON IPT.tagId = T.id
							INNER JOIN tbl_videoreference VRF
								ON VPU.videoId = VRF.videoId
						WHERE T.id = 1
						AND IPT.level = TPV.tagLevel
						AND groupId = "'.$groupId.'"
						GROUP BY VRF.videoId,T.id,tagLevel
						ORDER BY goods DESC, videoId
		     ) perTagLevelView
			GROUP BY
				videoId
			ORDER BY sumOfScore DESC
			
		) perTagScore
	';
	$result = mysql_query($maxcount_query);
	$row = mysql_fetch_assoc($result);

	$return["videos"] = $returnArr;
	$return["maxcount"] = $row["maxcount"];
	
	return $return;
}
function getAllGroups() {
	$returnArr = array();
	$tempRow = array();
	$query = '
		SELECT groupAltName,groupId,groupDescription 
			FROM `tbl_group` 
			ORDER BY groupAltName
	';
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){
		$tempRow["groupAltName"] = $row["groupAltName"];
		$tempRow["groupId"] = $row["groupId"];
		$tempRow["groupDescription"] = $row["groupDescription"];		
		array_push($returnArr, $tempRow);
	}
	return $returnArr;
}
function getGroupVideoCount($groupId) {
	$query = '
		SELECT 
			count(VRF.groupId) maxcount
			FROM
				tbl_videoreference VRF
					WHERE groupId = "'.$groupId.'"
	';
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	return $row;
}
if(isset($request->filter)) {
	$groupId = $request->groupId;
	$filter = $request->filter;
	$start = $request->start;
	$limit = $request->limit;
	// echo '1';
	if($filter=="top") {
		echo json_encode(getGroupVideo($groupId,$filter,$start,$limit));
	}
}
else if(isset($request->maxcount)) {
	$groupId = $request->groupId;
	echo json_encode(getGroupVideoCount($groupId));
}
else if(isset($request->getAllGroups)) {
	echo json_encode(getAllGroups());
}

mysql_error();
?>