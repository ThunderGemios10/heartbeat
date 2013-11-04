<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';
$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = isset($_SESSION["userinfo"]["email"])?$_SESSION["userinfo"]["email"]:"";
$collectionVideo = new MongoCollection($db, 'videos');

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
	global $useremail;
	$returnArr = array();
	$tempRow = array();
	// $query = '
	// 	SELECT groupAltName,groupId,groupDescription,bannerLink,dateModified,groupType
	// 		FROM `tbl_group`
	// 		WHERE creator = "'.$useremail.'"
	// 		ORDER BY groupAltName
	// ';
	$query = '
		SELECT groupAltName,groupId,groupDescription,bannerLink,dateModified,groupType
			FROM `tbl_group`		
			ORDER BY groupAltName
	';
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){	
		$tempRow["groupAltName"] = $row["groupAltName"];
		$tempRow["groupId"] = $row["groupId"];
		$tempRow["groupDescription"] = $row["groupDescription"];
		$tempRow["bannerLink"] = $row["bannerLink"];
		$tempRow["dateModified"] = $row["dateModified"];

		// $date =  $row["dateModified"];				
		// $tempRow["dateModified"] = date( 'd-m-Y', strtotime( $date ) );


		$tempRow["groupType"] = $row["groupType"];
		array_push($returnArr, $tempRow);
	}
	return $returnArr;
}
function getGroup($value) {
	$tempRow = array();
	$query = '
		SELECT groupAltName,groupId,groupDescription,bannerLink
			FROM `tbl_group` 
			WHERE groupId = "'.$value.'"
			ORDER BY groupAltName
	';
	// echo $query;
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
		$tempRow["groupAltName"] = $row["groupAltName"];
		$tempRow["groupId"] = $row["groupId"];
		$tempRow["groupDescription"] = $row["groupDescription"];		
		$tempRow["bannerLink"] = $row["bannerLink"];		
	return $tempRow;
}
function editGroup($editField='',$value='',$groupId='') {
	if($editField=="banner") {
		$query = '
			UPDATE `tbl_group` 
				SET bannerLink = "'.$value.'"				
				WHERE groupId = "'.$groupId.'"
		';
	}
	echo $query;
	$result = mysql_query($query);
	return array("response"=>$result);
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
function insertVideos($inserts) {
	$doc = (array)$inserts;
	//ID			
	$videoId = $doc["videoId"];			
	//TAGS INFO
	if(isset($doc["rating"])) {
		// $docTagsInfo["rating"] = isset($doc["rating"])?$doc["rating"]:'';
		$docTagsInfo["note"] = isset($doc["note"])?$doc["note"]:'';
		$docTagsInfo["username"] = isset($doc["username"])?$doc["username"]:'';
		$docTagsInfo["useremail"] = isset($doc["useremail"])?$doc["useremail"]:'';
		$docTagsInfo["postdate"] = new MongoDate();				
		if(isset($doc["rating"]))
			// $docTags = array('tag'=>$docTagsInfo["rating"],'count'=>1);
			if(is_array($doc["rating"])) {
				foreach($doc["rating"] as $docRate){
					array_push($docTagsInfo,$docRate);
				}	
			}
			else {
				array_push($docTagsInfo,$doc["rating"]);
			}		
	}
	else {
		// $docTagsInfo["rating"] = isset($doc["rating"])?$doc["rating"]:'';
		$docTagsInfo["note"] = isset($doc["note"])?$doc["note"]:'';
		$docTagsInfo["username"] = isset($doc["username"])?$doc["username"]:'';
		$docTagsInfo["useremail"] = isset($doc["useremail"])?$doc["useremail"]:'';
		$docTagsInfo["postdate"] = new MongoDate();
		// $docTags = array('tag'=>$docTagsInfo["rating"],'count'=>1);
	}
	//VIDEO INFO

	$docVideoInfo["snippet"] = (array)$doc["snippet"];
	$docVideoInfo["statistics"] = (array)$doc["statistics"];
	
	//DASHBOARD INFO
	$docDashboardInfo["statistics"] = isset($doc["dashboardInfo"])?(array)$doc["dashboardInfo"]:array();
	
	//CURRENT DATE
	$today = date("Y-m-d H:i:s"); //current date
	
	// $collectionVideo->findOne();
	if($collectionVideo->findOne(array("videoId"=>$videoId))) {
		if($collectionVideo->update(array("videoId"=>$videoId), array(
			'$addToSet'=>array(
				'tagsInfo'=>$docTagsInfo
				,'dashboardInfo'=>$docDashboardInfo
			)
		))) return "true";
		else "false";
	}
	else {//"tags"=>array($docTags), "comments"=>array($docTagsInfo),  ----> removed temporarily
		// $collectionVidStat->save(array("videoId"=>$videoId,"infoInstance"=>array($docVideoInfo)));
		$collectionVidStat->save(array("videoId"=>$videoId,"infoInstance"=>array($docVideoInfo)));
		if($collectionVideo->save(array("videoId"=>$videoId, "comments"=>array(), "videoInfo"=>$docVideoInfo,"dashboardInfo"=>array($docDashboardInfo)))) {
			// $cursor = $collectionVideo->find(array("videoId"=>$videoId));
			// $convertedObj = array();
			// foreach ($cursor as $qwe) {
				// array_push($convertedObj,$qwe);										
			// }
			// var_dump($convertedObj);
			// var_dump($doc);
			return "true";
		}
		else return "false";
	}
}
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
	else if(is_array($vid)){
		// var_dump($vid);
		$query = array('$or' => $vid);
		$cursor = $collectionVideo->find($query);
		foreach ($cursor as $doc) {
			array_push($convertedObj,$doc);
		}
	}
	return array_reverse($convertedObj);
}
function getVideos($value='') {
	$returnArr = array();
	$tempRow = array();
	$channelList = array();
	$videoList = array();
	if($value=="anyTV") {
		$query = '
			SELECT * 
				FROM `tbl_videoreference`		
				LIMIT 0,52
		';
	}
	else {
		$query = '
			SELECT * 
				FROM `tbl_videoreference`
				WHERE groupId = "'.$value.'"
				LIMIT 0,52
		';
	}
	$result = mysql_query($query);
	$i=0;
	// echo $query;
	while($row = mysql_fetch_assoc($result)){
		$video =  getLocalVideoById($row["videoId"]);
		// var_dump(expression)
		if(isset($video['err'])) {
			$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/../apirequest/youtube-api-video.php?videoId='.$row["videoId"]);
			$jsonresult = json_decode($contents);
			if(isset($jsonresult->id)) {
				$video = $jsonresult;
				insertVideos($video);
				$video = getLocalVideoById($row["videoId"]);	
			}			
		}
		$video["id"] = $row["id"];
		// var_dump($video);
		array_push($videoList,$video);
		// array_push($channelList, $tempRow);
		$i++;
	}
	$returnArr = $videoList;
	return $returnArr;	
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
	echo json_encode(getAllGroups(),JSON_NUMERIC_CHECK);
}
else if(isset($request->editfield)) {
	echo json_encode(editGroup($request->editfield,$request->value,$request->groupId));
}
else if(isset($request->getGroupById)) { echo json_encode(getGroup($request->groupId)); }
else if(isset($request->getVideoByGroup)) { echo json_encode(getVideos($request->groupId)); }
mysql_error();
?>