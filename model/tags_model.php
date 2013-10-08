<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

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

			$result = mysql_query('
				UPDATE `tbl_intensitypertag` 
					SET `defaultName`="'.$value->defaultName.'"
						,`alternateName`="'.$value->alternateName.'"
					WHERE tagId = '.$tag->tagId.'
						AND level = '.$value->level.'
			');		
		}
		return $toReturn;
	}
	return;
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
			else {
				$intensity["id"] = $row["id"];
				$intensity["defaultName"] = $row["defaultName"];
				$intensity["alternateName"] = $row["alternateName"];
				$intensity["level"] = $row["level"];
				array_push($tempRow["intensity"], $intensity);
				if($tempRow["type"]==4 && $tempRow["name"]=="Language"){
					array_push($languageIntensity, $intensity);
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
			else {
				$intensity["id"] = $row["id"];
				$intensity["defaultName"] = $row["defaultName"];
				$intensity["alternateName"] = $row["alternateName"];
				$intensity["level"] = $row["level"];
				array_push($tempRow["intensity"], $intensity);
				if($tempRow["type"]==4 && $tempRow["name"]=="Language"){
					array_push($languageIntensity, $intensity);
				}
			}
			
		}	
	}
	// var_dump($languageIntensity);
	// var_dump($tempRow);
	array_push($returnArr, $tempRow);	
	return $returnArr;
}
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
else if(isset($request->getTagsAlls)) {
	echo json_encode(getTagsAll());
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