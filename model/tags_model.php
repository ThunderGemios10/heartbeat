<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];

function addTag($tag){
	$result = mysql_query('
		INSERT INTO `tbl_tags`(`id`, `name`, `description`, `dateCreated`, `type`, `status`) 
			VALUES (NULL,"'.$tag->name.'","'.$tag->description.'",NOW(),"'.$tag->type.'",1)
	');
	$insertId = mysql_insert_id();
	$toReturn = array("insertId"=>$insertId);
	// var_dump($insertId);
	if($result==1) {
		$result = mysql_query('
			SELECT id
				FROM tbl_tags
				ORDER BY tbl_tags.id DESC
		');
		$row = mysql_fetch_assoc($result);
		foreach ($tag->intensity as $value) {
			$result = mysql_query('							
				INSERT INTO `tbl_intensitypertag`(`id`, `tagId`, `defaultName`, `alternateName`, `level`, `dateCreated`) 
					VALUES (NULL,"'.$row["id"].'","'.$value->defaultName.'","'.$value->alternateName.'","'.$value->level.'",NOW())
			');
		}
		return $toReturn;
	}
	return;
}
function editTag($tag){
	$result = mysql_query('
		UPDATE `tbl_tags` 
			SET `name`="'.$tag->name.'"
				,`description`="'.$tag->description.'"
			WHERE `id`='.$tag->tagId.'
	');
	$insertId = mysql_insert_id();
	$toReturn = array("insertId"=>$insertId);
	if($result==1) {
		foreach ($tag->intensity as $value) {			
				if($value->mainIntensityId==NULL) {
					$result = mysql_query('							
						INSERT INTO `tbl_intensitypertag`(`id`, `tagId`, `defaultName`, `alternateName`, `level`, `dateCreated`) 
							VALUES (NULL,"'.$value->id.'","'.$value->defaultName.'","'.$value->alternateName.'","'.$value->level.'",NOW())
					');
				}
				else {
					$result = mysql_query('
						UPDATE `tbl_intensitypertag` 
							SET `defaultName`="'.$value->defaultName.'"
								,`alternateName`="'.$value->alternateName.'"
							WHERE tagId = '.$tag->tagId.'
								AND level = '.$value->level.'
					');	
				}		
		}
		return $toReturn;
	}
	return;
}
function deleteIntensity($tag,$level) {
	$result = mysql_query('							
		DELETE FROM `tbl_intensitypertag`
			WHERE tagId = '.$tag.'
				AND level = '.$level.'
	');
	if($result==1) {
		return true;
	}
	return false;
}
function deactivateTag($tagId) {
	$result = mysql_query('
		UPDATE `tbl_tags` 
			SET `status`=0
			WHERE `id` = '.$tagId.'
	');
	if($result==1) {
		return true;
	}
	return false;
}
function activateTag($tagId) {
	$result = mysql_query('
		UPDATE `tbl_tags` 
			SET `status`=1
			WHERE `id` = '.$tagId.'
	');
	if($result==1) {
		return true;
	}
	return false;
}
function getTags($type){
	$result = mysql_query('		
		SELECT *,T.id mainTagId,IPT.id mainIntensityId, UNIX_TIMESTAMP(T.dateCreated) tagsDateCreated,UNIX_TIMESTAMP(IPT.dateCreated) intensityDateCreated
			FROM tbl_tags T
				LEFT JOIN tbl_intensitypertag IPT ON T.id = IPT.tagId
			WHERE type='.$type.'
				AND status=1		
			ORDER BY T.id
	');				
	$returnArr = array();
	$temp = "";
	$tempRow = array();
	$tempRow["intensity"] = array();
	$intensity = array();
	$languageIntensity = array();
	$gamesIntensity = array();
	$skip = false;
	$counter = 0;
	while($row = mysql_fetch_assoc($result)){
		if($row["tagId"]==$temp) {
			// echo "Equals";
			$intensity["mainIntensityId"] = $row["mainIntensityId"];
			$intensity["defaultName"] = $row["defaultName"];
			$intensity["alternateName"] = $row["alternateName"];
			$intensity["level"] = $row["level"];
			$intensity["intensityDateCreated"] = $row["intensityDateCreated"];
			array_push($tempRow["intensity"], $intensity);
			if($tempRow["type"]==4 && $tempRow["name"]=="Language"){			
				array_push($languageIntensity, $intensity);
			}
			else if($tempRow["type"]==5 && $tempRow["name"]=="Games"){			
				array_push($gamesIntensity, $intensity);
			}
		}
		else {
			if(count($tempRow)>1) {
				array_push($returnArr, $tempRow);
			}
			$temp = $row["mainTagId"];
			$tempRow = array();	
			$tempRow["tagId"] = $row["mainTagId"];
			$tempRow["name"] = $row["name"];
			$tempRow["description"] = $row["description"];
			$tempRow["tagsDateCreated"] = $row["tagsDateCreated"];
			$tempRow["type"] = $row["type"];
			$tempRow["status"] = $row["status"];
			$tempRow["type"] = $row["type"];
			$tempRow["intensity"] = array();
			if($tempRow["type"]==4&&$tempRow["name"]!="Language"){
				$tempRow["intensity"] = $languageIntensity;
			}
			else if($tempRow["type"]==5&&$tempRow["name"]!="Games"){
				$tempRow["intensity"] = $gamesIntensity;
			}
			else {
				$intensity["id"] = $row["id"];
				$intensity["defaultName"] = $row["defaultName"];
				$intensity["alternateName"] = $row["alternateName"];
				$intensity["level"] = $row["level"];
				array_push($tempRow["intensity"], $intensity);
				if($tempRow["type"]==4 && $tempRow["name"]=="Language"){
					array_push($languageIntensity, $intensity);
				}
				else if($tempRow["type"]==5 && $tempRow["name"]=="Games"){
					array_push($gamesIntensity, $intensity);
				}
			}
		
		}
	}	
	array_push($returnArr, $tempRow);
	return $returnArr;
}
function getTagsAll(){
	$result = mysql_query('
		SELECT *,T.id mainTagId,IPT.id mainIntensityId, UNIX_TIMESTAMP(T.dateCreated) tagsDateCreated,UNIX_TIMESTAMP(IPT.dateCreated) intensityDateCreated
			FROM tbl_tags T
				LEFT JOIN tbl_intensitypertag IPT ON T.id = IPT.tagId		
			ORDER BY T.id
	');
	$returnArr = array();
	$temp = "";
	$tempRow = array();
	$tempRow["intensity"] = array();
	$intensity = array();
	$languageIntensity = array();
	$gamesIntensity = array();
	while($row = mysql_fetch_assoc($result)){
		if($row["tagId"]==$temp) {
			// echo "Equals";
			$intensity["id"] = $row["id"];
			$intensity["mainIntensityId"] = $row["mainIntensityId"];
			$intensity["defaultName"] = $row["defaultName"];
			$intensity["alternateName"] = $row["alternateName"];
			$intensity["level"] = $row["level"];
			$intensity["intensityDateCreated"] = $row["intensityDateCreated"];
			array_push($tempRow["intensity"], $intensity);
			if($tempRow["type"]==4 && $tempRow["name"]=="Language"){			
				array_push($languageIntensity, $intensity);
			}
			else if($tempRow["type"]==5 && $tempRow["name"]=="Games"){
				array_push($gamesIntensity, $intensity);
			}
		}
		else {
			if(count($tempRow)>1) {
				array_push($returnArr, $tempRow);
			}
			// echo "Not Equals";
			$temp = $row["mainTagId"];
			$tempRow = array();	
			$tempRow["tagId"] = $row["mainTagId"];
			$tempRow["name"] = $row["name"];
			$tempRow["description"] = $row["description"];
			$tempRow["tagsDateCreated"] = $row["tagsDateCreated"];
			$tempRow["type"] = $row["type"];
			$tempRow["status"] = $row["status"];
			$tempRow["type"] = $row["type"];
			$tempRow["intensity"] = array();
			if($tempRow["type"]==4&&$tempRow["name"]!="Language"){				
				$tempRow["intensity"] = $languageIntensity;
			}
			else if($tempRow["type"]==5&&$tempRow["name"]!="Games"){				
				$tempRow["intensity"] = $gamesIntensity;
			}
			else {
				$intensity["id"] = $row["id"];
				$intensity["mainIntensityId"] = $row["mainIntensityId"];
				$intensity["defaultName"] = $row["defaultName"];
				$intensity["alternateName"] = $row["alternateName"];
				$intensity["level"] = $row["level"];
				$intensity["intensityDateCreated"] = $row["intensityDateCreated"];
				array_push($tempRow["intensity"], $intensity);
				if($tempRow["type"]==4 && $tempRow["name"]=="Language"){
					array_push($languageIntensity, $intensity);
				}
				else if($tempRow["type"]==5 && $tempRow["name"]=="Games"){
					array_push($gamesIntensity, $intensity);
				}
			}
			
		}	
	}
	// var_dump($languageIntensity);
	// var_dump($tempRow);
	array_push($returnArr, $tempRow);
	return $returnArr;
}

function getAllTagsJSON(){
	$contents = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/basictags.json');		
	// var_dump($contents);
	$request = json_decode($contents);
	return $request;
}
function getCurrentUserFreeTags () {
	global $useremail;
	$returnArr = array();
	$query = '
		SELECT tagName FROM `tbl_freeformtags` FFT
			WHERE tagCreator = "'.$useremail.'"
	';
	// echo $query;
	$result = mysql_query($query);
	if($result) {
		while($row = mysql_fetch_assoc($result)){
			$tempRow = $row["tagName"];		
			array_push($returnArr, $tempRow);	
		}
		return $returnArr;
	}	
	else 
		return;
}
function getCurrentVideoUserFreeTags($value) {
	global $useremail;
	$returnArr = array();
	$query = '
		SELECT tagName FROM `tbl_freeformtags` FFT
			INNER JOIN `tbl_freeformtagspervideo` FPV
				ON FFT.id = FPV.`freeformtagId`
			WHERE videoId = "'.$value.'"
				AND tagger = "'.$useremail.'"
	';
	// echo $query;
	$result = mysql_query($query);
	if($result) {
		while($row = mysql_fetch_assoc($result)){
			$tempRow = $row["tagName"];		
			array_push($returnArr, $tempRow);	
		}	
	}	
	return $returnArr;
}
function getFreeTags($mode,$start,$limit) {	
	global $useremail;
	$returnArr = array();
	if($mode=="all") {		
		$query = '
			SELECT tagName,tagCreator, COUNT(videoId) numberOfTimesUsed FROM `tbl_freeformtagspervideo` FPV
				INNER JOIN `tbl_freeformtags` FFT
					ON FPV.freeformtagId = FFT.`id`
				GROUP BY tagName
				ORDER BY numberOfTimesUsed DESC
				LIMIT '.$start.','.$limit.'
		';
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			$tempRow["tagName"] = $row["tagName"];
			$tempRow["tagCreator"] = $row["tagCreator"];
			$tempRow["numberOfTimesUsed"] = $row["numberOfTimesUsed"];			
			array_push($returnArr, $tempRow);	
		}
	}
	else if($mode=="currentuser") {
		$query = '
			SELECT tagName,COUNT(videoId) numberOfTimesUsed FROM `tbl_freeformtagspervideo` FPV
				RIGHT JOIN `tbl_freeformtags` FFT
					ON FPV.freeformtagId = FFT.`id`
				WHERE tagCreator = "'.$useremail.'"
				GROUP BY tagName
				ORDER BY numberOfTimesUsed DESC
				LIMIT '.$start.','.$limit.'
		';		
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			$tempRow["tagName"] = $row["tagName"];
			$tempRow["numberOfTimesUsed"] = $row["numberOfTimesUsed"];
			array_push($returnArr, $tempRow);	
		}	
	}
	// echo $query; 
	return $returnArr;
}


/***********************************************************************************/



if(isset($request->tagArr)) {
	// var_dump('qwe');
	$values = $request->tagArr;
	echo json_encode(addTag($values));
}
else if(isset($request->editTagArr)) {
	$values = $request->editTagArr;
	// var_dump($values);
	echo json_encode(editTag($values));
}
else if(isset($request->tagIdToDeactivate)) {
	$values = $request->tagIdToDeactivate;
	echo deactivateTag($values);
}
else if(isset($request->tagIdToActivate)) {
	$values = $request->tagIdToActivate;
	echo activateTag($values);
}
else if(isset($request->tagIdDeleteIntensity)) {
	$tagId = $request->tagIdDeleteIntensity;
	$level = $request->level;
	echo deleteIntensity($tagId,$level);
}
else if(isset($request->getTagsAll)) {
	echo json_encode(getTags(2));
}
else if(isset($request->getTagsPrimaryAll)) {
	echo json_encode(getTags(1));
}
else if(isset($request->getSecondaryTagsAll)) {
	echo json_encode(getTags(3));
}
else if(isset($request->getTertiaryTagsAll)) {
	echo json_encode(getTags(4));
}
else if(isset($request->getGamesAll)) {
	echo json_encode(getTags(5));
}
else if(isset($request->getTagsAlls)) {
	echo json_encode(getTagsAll());
}
else if(isset($request->getAllTagsJSON)) {
	echo json_encode(getAllTagsJSON(),JSON_NUMERIC_CHECK);
}
else if(isset($request->getCurrentVideoUserFreeTags)) {
	$value = $request->getCurrentVideoUserFreeTags;
	echo json_encode(getCurrentVideoUserFreeTags($value));
}
else if(isset($request->getCurrentUserFreeTags)) {
	echo json_encode(getCurrentUserFreeTags());
}
else if(isset($request->getTopFreeTags)) {
	$start = $request->start;
	$limit = $request->limit;
	echo json_encode(getFreeTags("all",$start,$limit));
}
else if(isset($request->getTopCurrentUserFreeTags)) {	
	$start = $request->start;
	$limit = $request->limit;
	echo json_encode(getFreeTags("currentuser",$start,$limit));
}
else {
	// $result = mysql_query('SELECT *
	// 	FROM tbl_tags T
	// 	INNER JOIN tbl_intensitypertag IPT ON T.id = IPT.tagId
	// 	WHERE type=2
	// 	ORDER BY T.id
	// ');
	// $returnArr = array();
	// $temp = "";
	// $tempRow = array();
	// $tempRow["intensity"] = array();
	// $intensity = array();
	// while($row = mysql_fetch_assoc($result)){		
	// 	if($row["tagId"]==$temp) {
	// 		echo "Equals";
	// 		$intensity["id"] = $row["id"];
	// 		$intensity["defaultName"] = $row["defaultName"];
	// 		$intensity["alternateName"] = $row["alternateName"];
	// 		$intensity["level"] = $row["level"];
	// 		array_push($tempRow["intensity"], $intensity);
	// 		// var_dump($tempRow);
	// 	}
	// 	else {
	// 		if(count($tempRow)>1) {
	// 			array_push($returnArr, $tempRow);
	// 		}
	// 		echo "Not Equals";
	// 		$temp = $row["tagId"];		
	// 		$tempRow = array();	
	// 		$tempRow["tagId"] = $row["tagId"];
	// 		$tempRow["name"] = $row["name"];
	// 		$tempRow["description"] = $row["description"];
	// 		$tempRow["dateCreated"] = $row["dateCreated"];
	// 		$tempRow["type"] = $row["type"];
	// 		$tempRow["status"] = $row["status"];
	// 		$tempRow["type"] = $row["type"];
	// 		$tempRow["intensity"] = array();
	// 		$intensity["id"] = $row["id"];
	// 		$intensity["defaultName"] = $row["defaultName"];
	// 		$intensity["alternateName"] = $row["alternateName"];
	// 		$intensity["level"] = $row["level"];
	// 		array_push($tempRow["intensity"], $intensity);
	// 	}
	// }

	// array_push($returnArr, $tempRow);
	// foreach ($returnArr as $row) {
	// 	echo '<hr/><pre>' . print_r($row,true) . "</pre>"; 
	// }
}
// mysql_error();
?>