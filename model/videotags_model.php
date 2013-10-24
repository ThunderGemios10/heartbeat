<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];
function modifyVideoFreeformTag($videoId,$freeformtags) {	
	global $useremail;
	var_dump($freeformtags);
	if($freeformtags!=NULL) {
		echo 'it deletes';
		$query = '
			DELETE FROM `tbl_freeformtagspervideo`
				WHERE `tagger`=  "'.$useremail.'"
		';	
		$result = mysql_query($query);
		foreach ($freeformtags as $value) {
			// var_dump($value);
			if($value!="") {
				$value = strtolower($value);
				$query = '
					INSERT INTO `tbl_freeformtags` (
					   id
					  ,`tagName`
					  ,`tagCreator`
					  ,`dateCreated`
					  ,`dateModified`
					  ,`status`
					) VALUES
					  (
					  	NULL
					    ,"'.$value.'"
					    ,"'.$useremail.'"
					    ,NOW()
					    ,NOW()
					    ,1
					  )
				';			
				$result = mysql_query($query);
				if($result) {
					$lastId = mysql_insert_id();
					$query = '
						INSERT INTO `tbl_freeformtagspervideo` (
						   id
						  ,`freeformtagId`
						  ,`tagger`
						  ,`videoId`
						  ,`dateCreated`
						  ,`dateModified`
						  ,`status`
						) VALUES
						  (
						  	NULL
						    ,"'.$lastId.'"
						    ,"'.$useremail.'"					    
						    ,"'.$videoId.'"
						    ,NOW()
						    ,NOW()
						    ,1
						  )
					';
					$result = mysql_query($query);
				}
				else {
					$query = '
						SELECT id FROM tbl_freeformtags
							WHERE tagName = "'.$value.'"
								AND tagCreator = "'.$useremail.'" 
							LIMIT 0,1
					';
					// echo "72".$query;
					$result = mysql_query($query);
					$row = mysql_fetch_assoc($result);
					$query = '
						INSERT INTO `tbl_freeformtagspervideo` (
						   id
						  ,`freeformtagId`
						  ,`tagger`
						  ,`videoId`
						  ,`dateCreated`
						  ,`dateModified`
						  ,`status`
						) VALUES
						  (
						  	NULL
						    ,"'.$row["id"].'"
						    ,"'.$useremail.'"
						    ,"'.$videoId.'"
						    ,NOW()
						    ,NOW()
						    ,1
						  )
					';
					$result = mysql_query($query);
				}
			}
		}		
	}
	else {
		$query = '
			DELETE FROM `tbl_freeformtagspervideo`
				WHERE `tagger`=  "'.$useremail.'"
		';	
		$result = mysql_query($query);
	}
	return;
}
function modifyVideoTag($videoId,$videoTags){
	global $useremail;
	$result = mysql_query('
		SELECT * 
		FROM tbl_videoperuser 
		WHERE videoId = "'.$videoId.'"
		AND useremail = "'.$useremail.'"
	');
	if($videoperuser = mysql_fetch_assoc($result)) {
		$videoperuserId = $videoperuser["id"];
		mysql_query('
				UPDATE  `tbl_videoperuser`
				SET dateModified = NOW()
				WHERE videoId = "'.$videoId.'"
				AND useremail = "'.$useremail.'"
		');
	}
	else {
		mysql_query('
			INSERT INTO `tbl_videoperuser` (`videoId`,`useremail`,`dateCreated`,`dateModified`) VALUES (\''.$videoId.'\',\''.$useremail.'\',NOW(),NOW())
		');
		$result = mysql_query('
			SELECT * 
			FROM tbl_videoperuser
			WHERE videoId = "'.$videoId.'"
			AND useremail = "'.$useremail.'"
		');
		if($videoperuser = mysql_fetch_assoc($result)) {
			$videoperuserId = $videoperuser["id"];
		}	
	}

	$result = mysql_query(
		'DELETE FROM `tbl_tagspervideo`
			WHERE videoperuserId = '.$videoperuserId.'
	');
	$i = 0;
	$insertQuery = 'INSERT INTO `tbl_tagspervideo` (`id`,`tagId`,`tagLevel`,`videoperuserId`) VALUES';
	foreach($videoTags as $tag){
		if($i!=0) $insertQuery.=',';
		if(isset($tag->selectedLevel)){
			if($tag->selectedLevel==NULL) {
				$selectedLevel = 0;
			}
			else {
				$selectedLevel = $tag->selectedLevel;
			}
		}
		else {
			$selectedLevel = 0;
		}	
		$insertQuery.='(NULL,\''.$tag->tagId.'\',\''.$selectedLevel.'\',\''.$videoperuserId.'\')';
		$i++;
	}
	var_dump($insertQuery);
	$result = mysql_query($insertQuery);
	if($result) return true;
	return false;
	mysql_error();	
}
function getVideoTags($videoId,$type) {
	global $useremail;
	$returnArr = array();
	$tempRow = array();
	if($type==4){
		$query = '
			SELECT *,TPV.tagId primaryId 
				FROM tbl_videoperuser VPU 
					INNER JOIN tbl_tagspervideo TPV 
						ON VPU.id = TPV.videoperuserId 
					INNER JOIN tbl_tags T 
						ON T.id = TPV.tagId	
					LEFT JOIN tbl_intensityPerTag IPT 
						ON IPT.tagId = T.id 
							AND IPT.level = TPV.tagLevel				
				WHERE videoId = "'.$videoId.'"
				AND useremail = "'.$useremail.'"
				AND type = "'.$type.'"				
		';
	}
	else if($type==5){
		$query = '
			SELECT *,TPV.tagId primaryId 
				FROM tbl_videoperuser VPU 
					INNER JOIN tbl_tagspervideo TPV 
						ON VPU.id = TPV.videoperuserId 
					INNER JOIN tbl_tags T 
						ON T.id = TPV.tagId
					LEFT JOIN tbl_intensityPerTag IPT 
						ON IPT.tagId = T.id 
							AND IPT.level = TPV.tagLevel				
				WHERE videoId = "'.$videoId.'"
				AND useremail = "'.$useremail.'"
				AND type = "'.$type.'"
		';
	}
	else if($type==0){
		$query = '
			SELECT *,TPV.tagId primaryId 
				FROM tbl_videoperuser VPU 
					INNER JOIN tbl_tagspervideo TPV 
						ON VPU.id = TPV.videoperuserId 
					INNER JOIN tbl_tags T 
						ON T.id = TPV.tagId
					LEFT JOIN tbl_intensityPerTag IPT 
						ON IPT.tagId = T.id
							AND IPT.level = TPV.tagLevel
				WHERE videoId = "'.$videoId.'"
				AND useremail = "'.$useremail.'"			
				
		';
		// echo $query;
	}
	else {
		$query = '
			SELECT *,TPV.tagId primaryId 
				FROM tbl_videoperuser VPU 
					INNER JOIN tbl_tagspervideo TPV 
						ON VPU.id = TPV.videoperuserId 
					INNER JOIN tbl_tags T 
						ON T.id = TPV.tagId
					LEFT JOIN tbl_intensityPerTag IPT 
						ON IPT.tagId = T.id 
							AND IPT.level = TPV.tagLevel
				WHERE videoId = "'.$videoId.'"
				AND useremail = "'.$useremail.'"
				AND type = "'.$type.'"				
		';		
	}
	
// print_r($query);
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){
		$tempRow["tagId"] = $row["primaryId"];
		$tempRow["tagLevel"] = $row["tagLevel"];
		$tempRow["name"] = $row["name"];
		$tempRow["type"] = $row["type"];		
		if($type!=4 && $type!=5) $tempRow["prefix"] = $row["defaultName"];		
		array_push($returnArr, $tempRow);	
	}
	return $returnArr;
}
function getVideoTagsFeed($videoId,$user) {
	global $useremail;
	$returnArr = array();
	$tempRow = array();		
	$query = '
		SELECT *,TPV.tagId primaryId 
			FROM tbl_videoperuser VPU 
				INNER JOIN tbl_tagspervideo TPV 
					ON VPU.id = TPV.videoperuserId 
				INNER JOIN tbl_tags T 
					ON T.id = TPV.tagId
				INNER JOIN tbl_intensityPerTag IPT 
					ON IPT.tagId = T.id
			WHERE videoId = "'.$videoId.'"
			AND useremail = "'.$user.'"		
			AND IPT.level = TPV.tagLevel
	';		
	

	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)){
		$tempRow["tagId"] = $row["primaryId"];
		$tempRow["tagLevel"] = $row["tagLevel"];
		$tempRow["name"] = $row["name"];
		$tempRow["type"] = $row["type"];		
		array_push($returnArr, $tempRow);	
	}
	return $returnArr;
}

if(isset($request->getVideoTags)) {
	$videoId = $request->vid;
	if(isset($request->type)) {
		$type = $request->type;	
	}
	else {
		$type = 0;	
	}
	echo json_encode(getVideoTags($videoId,$type));	
}
else if(isset($request->getVideoTagsFeed)) {
	$videoId = $request->vid;
	$user = $request->user;

	echo json_encode(getVideoTagsFeed($videoId,$user));	
}
else if(isset($request->mode)) {
	if($request->mode == 1) {
		$videoId = $request->vidId;
		$videoTags = $request->videoTags;	
		echo json_encode(modifyVideoTag($videoId,$videoTags));
		$freeformtags = $request->free;
		echo json_encode(modifyVideoFreeformTag($videoId,$freeformtags));
	}
}
else {
	$query = 'SELECT  *,VPU.id vpuId
		FROM tbl_videoperuser VPU 
		INNER JOIN tbl_tagspervideo TPV ON VPU.id = TPV.videoperuserId
		INNER JOIN tbl_tags T ON T.id = TPV.tagId
		INNER JOIN tbl_intensitypertag TPT ON TPT.id = TPV.tagIntensity
		WHERE videoId = "aO9ArAunYIQ"
		AND useremail = "rob@any.tv"
	';
	$result = mysql_query($query);
	$returnArr = array();
	$i = 0;
	while($row = mysql_fetch_assoc($result)){		
		array_push($returnArr, $row);
	}

	foreach ($returnArr as $row) {
		echo '<hr/><pre>' . print_r($row,true) . "</pre>"; 
	}
}

mysql_error();
?>