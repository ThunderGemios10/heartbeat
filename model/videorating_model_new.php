<?php  
// Config  
session_start();
// $dbhost = 'localhost';  
$dbname = 'heartbeatdb';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

// echo "2";
// var_dump($request);
$collectionVideo = new MongoCollection($db, 'videos');
$collectionVidStat = new MongoCollection($db, 'videoStats');
$collectionUserprofile = new MongoCollection($db, 'userprofile');
$collectionAuthuser = new MongoCollection($db, 'authuser');

if(isset($_SESSION["userId"])) {
	$userId = $_SESSION["userId"]->{'$id'};
}

// $collectionVideo->remove();
// $collectionVideo->remove(array('_id'=>new ObjectId));
// $collectionVideo->remove(array('_id' => new MongoId('52031eabfc30e5680d00000c')));

function insertVideos($inserts,$mode) {
	// var_dump($_SESSION["userId"]->{'$id'});
	global $userId;
	if(isset($userId)) {
	global $collectionVideo;
	global $collectionVidStat;
	global $collectionUserprofile;
	global $collectionAuthuser;
	
	if($mode=="live") {
		$doc = (array)$inserts;
		//ID
		$videoId = $doc["videoId"];

		$docTags = $doc["rating"];
		
		//TAGS INFO				
		$docTagsInfo["rating"] = $doc["rating"];
		$docTagsInfo["note"] = $doc["note"];
		$docTagsInfo["username"] = $doc["username"];
		$docTagsInfo["useremail"] = $doc["useremail"];
		$docTagsInfo["postdate"] = new MongoDate();
		//VIDEO INFO

		$docVideoInfo["snippet"] = (array)$doc["snippet"];
		$docVideoInfo["statistics"] = (array)$doc["statistics"];
		
		//CURRENT DATE
		$today = date("Y-m-d H:i:s"); //current date		
		
		$ratedVideo = array($videoId,$docTagsInfo["postdate"]);
		
		// $collectionVideo->findOne();
		if($collectionVideo->findOne(array("videoId"=>$videoId))){
			$collectionVideo->update(array("videoId"=>$videoId), array(
				'$addToSet'=>array(
					'tagsInfo'=>$docTagsInfo
				)
			));
		}
		else {
			// $collectionVidStat->save(array("videoId"=>$videoId,"infoInstance"=>array($docVideoInfo)));
			$collectionVidStat->save(array("videoId"=>$videoId,"infoInstance"=>array($docVideoInfo)));
			$collectionUserprofile->save(array("userId"=>$userId,"ratedVideos"=>array($ratedVideo)));
			return $collectionVideo->save(array("videoId"=>$videoId,"tags"=>array($docTagsInfo),"tagsInfo"=>array($docTagsInfo),"videoInfo"=>$docVideoInfo));		
		}
	}
	else if($mode=="rank") {
		
			// var_dump($inserts);
			$doc = (array)$inserts;
			echo "--------------------------------------------------------------------\n";
			$docTag =  (array)$doc["tags"];
			$docComments = $doc["comments"];
			var_dump($_SESSION);
			$docComments["username"] = $_SESSION["userinfo"]["name"];
			$docComments["useremail"] = $_SESSION["userinfo"]["email"];
			// var_dump($docComments->username);
			$docComments["postdate"] = new MongoDate();
			$docPostdate = $docComments["postdate"];
			$videoId = $doc["videoId"];		
			// $cleanEmail = urldecode(str)$docComments["useremail"]);
			$cleanEmail = str_replace('.', '[period]' , $docComments["useremail"]);
			$ratedVideo = array($videoId,$docPostdate);
			var_dump($docTag);
			echo "--------------------------------------------------------------------\n";
			echo "--------------------------------------------------------------------\n";
			var_dump($docComments);
			foreach ($docTag as $tag) {
				var_dump($tag);
				if($collectionVideo->findOne(array("videoId"=>$videoId), array("_id"=>NULL,"tags"=>array('$elemMatch'=>array("tag"=>$tag->rate))))) {
					$collectionVideo->update(
						array(	//condition
							'videoId'=>$videoId
							,'tags.tag'=>$tag->rate
						)
						,array(	//modify
							'$inc' => array('tags.$.count'=>1)
						)
					);
					$collectionVideo->update(
						array(	//condition
							'videoId'=>$videoId						
							,'comments.useremail'=>$docComments["useremail"]
						)
						,array(	//modify
							'$set' => array(
								"lastModified" => $docPostdate
								,'comments.$.postdate' => $docPostdate
							)
						)
					);
					$collectionVideo->update(
						array(	//condition
							'videoId'=>$videoId						
							// ,'comments.useremail'=>$docComments["useremail"]
							,"comments.".$cleanEmail.".$.rate"=>$tag->rate
						)
						,array(	//modify
							'$set' => array(
							 	"comments.".$cleanEmail.".$.intensity" => $tag->intensity
							)
						)
					);
					echo"\n--".$tag->rate."-".$tag->intensity."\n";
					echo "1\n";
				}
				else if($collectionVideo->findOne(array("videoId"=>$videoId), array("_id"=>NULL,"comments"=>array('$elemMatch'=>array("useremail"=>$docComments["useremail"]))))) {
					$collectionVideo->update(
						array(	//condition
							'videoId'=>$videoId
							,'comments.useremail'=>$docComments["useremail"]
						)
						,array( //modify
						'$addToSet'=>array(
							'comments.$.rating'=>$tag
							// ,'tags'=>array('tag'=>$tag->rate,'count'=>1)
						)
						,'$set' => array("lastModified" => $docPostdate)
					));
					echo "2\n";
				}
				else {
					$collectionVideo->update(
						array(	//condition
							'videoId'=>$videoId
							// ,'comments.$.useremail'=>$docComments->useremail
						)
						,array(
							'$addToSet'=>array(
								'tags'=>array('tag'=>$tag->rate,'count'=>1)
								
							)
							,'$set' => array(							
								"lastModified" => $docPostdate
								,'comments'=>array($cleanEmail=>$docComments)
							)
						)
					);
					echo "3\n";
				}
				echo "-----------------------------------------------------------------------------------";
			}
			if(!($collectionUserprofile->findOne(array("userId"=>$userId)))){
				$collectionUserprofile->insert(array('userId'=>$userId));
			}
			$collectionUserprofile->update(array("userId"=>$userId), array(
				'$addToSet'=>array(
					'ratedVideos'=>$ratedVideo
				)
				,'$set' => array("lastModified" => $docPostdate)
			));
		}
		else if($mode=="upload") {
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
	}
}
function getLocalVideos($mode,$limit) {
	global $collectionVideo;
	$convertedObj = array();
	if($mode=='all') {
		$cursor = $collectionVideo->find()->sort(array('lastModified'=>-1));
	}
	else if($mode=='unrated') {
		$cursor = $collectionVideo->find(array("comments"=>array('$size'=>0)),array("videoInfo"=>1,"videoId"=>1))->limit($limit);
	}
	else if($mode=='rated') {
		$randNum = rand(10, ($collectionVideo->count())-$limit);		
		$cursor = $collectionVideo->find(array(),array("videoInfo"=>1,"videoId"=>1))->limit($limit)->skip($randNum);		
	}
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
	}
	return $convertedObj;
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

if(isset($request->videosArr)){
	$values = (array)$request->videosArr;
	$mode = $request->mode;
	// var_dump($values[0]);
	// echo json_encode($values[0],JSON_NUMERIC_CHECK);

	insertVideos($values[0],$mode);
}
else if(isset($request->all)) {
	echo json_encode(getLocalVideos('all',0),JSON_NUMERIC_CHECK);
}
else if(isset($request->unrated)){
	echo json_encode(getLocalVideos('unrated',$request->limit),JSON_NUMERIC_CHECK);
}
else if(isset($request->rated)){
	echo json_encode(getLocalVideos('rated',$request->limit),JSON_NUMERIC_CHECK);
	// echo "HEY!".$request->limit;
}
else if(isset($request->vid)){
	$vid = $request->vid;
	echo json_encode(getLocalVideoById($vid),JSON_NUMERIC_CHECK);
}
else {	
	// $searchText = isset($_GET["searchText"])?$_GET["searchText"]:'';
	// $randNum = rand(10, $collectionVideo->count());
	// echo $randNum;
	// $cursor = $collectionVideo->find(array(),array("videoInfo"=>1,"videoId"=>1))->limit(10)->skip($randNum);		
	// $convertedObj = array();
	// foreach ($cursor as $doc) {
	// 	array_push($convertedObj,$doc);
	// 	echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
	// }
	// echo "<hr/>";

	// $regexObj = new MongoRegex("/^".$searchText."/i"); 
	// echo "Collection - Search - \" ".$searchText." \"";
	// $i = 1;
	// $cursor = $collectionVideo->find(array('$or'=> array(
	// 		array("videoInfo.snippet.title"=>$regexObj)
	// 		,array("videoInfo.snippet.description"=>$regexObj)
	// 		,array("videoInfo.snippet.channelTitle"=>$regexObj)
	// 	)
	// ));
	// $convertedObj = array();
	// foreach ($cursor as $doc) {
	// 	array_push($convertedObj,$doc);
	// 	echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
	// 	$i++;		
	// }
	echo "<hr/>";
	echo "Collection - Videos";
	$i = 1;
	$cursor = $collectionVideo->find();
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
		echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
		$i++;		
	}
	echo "<hr/>";
	// echo "Collection - Authuser";
	// $i = 1;
	// $cursor = $collectionAuthuser->find();
	// $convertedObj = array();
	// foreach ($cursor as $doc) {
	// 	array_push($convertedObj,$doc);
	// 	echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
	// 	$i++;		
	// }	
	// echo json_encode($convertedObj);
}

?>