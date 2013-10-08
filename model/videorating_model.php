<?php
require "connection.php";

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//functions
function insert_videos($table, $inserts) {
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
	print_r('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`,`postdate`) VALUES (\''.implode('\',\'', $values).'\',NOW())');
    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`,`postdate`) VALUES (\''.implode('\',\'', $values).'\',NOW())');
}
function insert_ratings($table, $inserts) {
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
	// print_r('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`,`dateCreated`) VALUES (\''.implode('\',\'', $values).'\',NOW())');
    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`,`dateCreated`) VALUES (\''.implode('\',\'', $values).'\',NOW())');
}
function update_rating($table, $inserts) {	
	// print_r("UPDATE tbl_rating SET name = '".mysql_real_escape_string($inserts['name'])."',description='".mysql_real_escape_string($inserts['desc'])."' WHERE id=".mysql_real_escape_string($inserts['id']));
	return mysql_query("UPDATE tbl_rating SET name = '".mysql_real_escape_string($inserts['name'])."',description='".mysql_real_escape_string($inserts['desc'])."' WHERE id=".mysql_real_escape_string($inserts['id']));
}
function deactivate_rating($table, $id) {	
	print_r("UPDATE tbl_rating SET status=2 WHERE id=".mysql_real_escape_string($id));
	return mysql_query("UPDATE tbl_rating SET status=2 WHERE id=".mysql_real_escape_string($id));
}
function activate_rating($table, $id) {
	print_r("UPDATE tbl_rating SET status=1 WHERE id=".mysql_real_escape_string($id));
	return mysql_query("UPDATE tbl_rating SET status=1 WHERE id=".mysql_real_escape_string($id));
}
function getRatings($id) {
	// echo '\n\n\nSELECT rating, note, postdate FROM tbl_video WHERE videoId = \''.$id.'\' ORDER BY postdate DESC LIMIT 0,1';	
	$result = mysql_query('SELECT rating, note, postdate, `username`, `useremail` FROM tbl_video WHERE videoId = \''.$id.'\' ORDER BY postdate DESC LIMIT 0,1');	
	$row = mysql_fetch_assoc($result);
	$ratingsAndNote = array(
		"rate" => ($row["rating"]==NULL|$row["rating"]==""?0:$row["rating"])
		,"note" => ($row["note"]==NULL|$row["note"]==""?"":$row["note"])
		,"postdate" => ($row["postdate"]==NULL|$row["postdate"]==""?"":$row["postdate"])
		,"username" => ($row["username"]==NULL|$row["username"]==""?"":$row["username"])
		,"useremail" => ($row["useremail"]==NULL|$row["useremail"]==""?"":$row["useremail"])
	);
	return $ratingsAndNote;
}
function getRatings_unfiltered($id){
	$ratingsAndNote = array();
	$return = array();
	$result = mysql_query('SELECT rating, note, postdate, `username`, `useremail` FROM tbl_video WHERE videoId = \''.$id.'\' ORDER BY postdate DESC');	
	while($row = mysql_fetch_assoc($result)) {
		$ratingsAndNote = array(
			"rate" => ($row["rating"]==NULL|$row["rating"]==""?0:$row["rating"])
			,"note" => ($row["note"]==NULL|$row["note"]==""?"":$row["note"])
			,"postdate" => ($row["postdate"]==NULL|$row["postdate"]==""?"":$row["postdate"])
			,"username" => ($row["username"]==NULL|$row["username"]==""?"":$row["username"])
			,"useremail" => ($row["useremail"]==NULL|$row["useremail"]==""?"":$row["useremail"])
		);
		array_push($return,$ratingsAndNote);
	}
	return $return;
}
function getTagsPerVid($id){
	$return = array();
	$result = mysql_query('SELECT videoId, rating,`name`, COUNT(rating) countPerRate FROM tbl_video V INNER JOIN tbl_rating R ON V.`rating` = R.`id`	WHERE videoId = "'.$id.'" GROUP BY videoId,rating,`name`');	
	while($row = mysql_fetch_assoc($result)){	
		array_push($return,$row);
	}
	return $return;
}
function getLocalVideos(){
	$result = mysql_query('SELECT videoId,postdate,rating 
							FROM (SELECT videoId,postdate,rating FROM tbl_video ORDER BY postdate DESC) AS A INNER JOIN tbl_rating B ON A.rating = B.`id`
							WHERE B.status = 1
							GROUP BY videoId ORDER BY A.postdate DESC');	
	$returnArr = array();
	while($row = mysql_fetch_assoc($result)) {
		$all = array(
			"videoId" => $row["videoId"]
		);
		array_push($returnArr,$all);
	}
	return $returnArr;
}
function getProjectRating($projectId){
	//echo 'SELECT * FROM tbl_rating WHERE projectId = '.$projectId;
	$result = mysql_query('SELECT * FROM tbl_rating WHERE projectId = '.$projectId);
	$returnArr = array();
	while($row = mysql_fetch_assoc($result)) {
		$ratingRow = array(
			"id" => $row["id"]
			,"name" => $row["name"]
			,"description" => $row["description"]
			,"projectId" => $row["projectId"]
			,"dateCreated" => $row["dateCreated"]
			,"status" => $row["status"]
		);
		array_push($returnArr,$ratingRow);
	}
	return $returnArr;
}
function getProjectRating_filtered($projectId){
	$result = mysql_query('SELECT * FROM tbl_rating WHERE projectId = '.$projectId.' AND status = 1');
	$returnArr = array();
	while($row = mysql_fetch_assoc($result)) {
		$ratingRow = array(
			"id" => $row["id"]
			,"name" => $row["name"]
			,"description" => $row["description"]
			,"projectId" => $row["projectId"]
			,"dateCreated" => $row["dateCreated"]
			,"status" => $row["status"]
		);
		array_push($returnArr,$ratingRow);
	}
	return $returnArr;
}




if(isset($request->vidRates)) {// insert ratings------------------------------------------------------------------------------------------------------------------------------------------------
	$values = $request->vidRates;	
	// var_dump($values);
	foreach ($values as $value) {
		$value =  (array)$value; // cast to array
		if(insert_videos('tbl_video',$value))
			echo 'true';
		else 
			echo 'false';
	}
}
else if(isset($request->filtered)&&isset($request->ids)){ // get ratings------------------------------------------------------------------------------------------------------------------------------------------------
	$values = $request->ids;
	$ratingPerVid = array();
	foreach ($values as $value) {
		$temp = getRatings($value);
		$arr = array(
			"id" => $value			
			,"rate" => $temp["rate"]
			,"note" => $temp["note"]
			,"postdate" => $temp["postdate"]
			,"ratedByName" => $temp["username"]	
			,"ratedByEmail" => $temp["useremail"]				
		);		
		array_push($ratingPerVid,$arr);
	}
	echo json_encode($ratingPerVid);
}
else if(isset($request->unfiltered)){ // get ratings------------------------------------------------------------------------------------------------------------------------------------------------
	$values = $request->ids;
	$ratingPerVid = array();
	foreach ($values as $value) {
		$temp = getRatings_unfiltered($value);
		$arr = array(
			"id" => $value
			,"tagsInfo" => $temp			
		);		
		array_push($ratingPerVid,$arr);
	}
	// print_r($ratingPerVid[0]);
	echo json_encode($ratingPerVid);
}
else if(isset($request->tagsPerVid)){ // get ratings------------------------------------------------------------------------------------------------------------------------------------------------
	$values = $request->ids;
	$ratingPerVid = array();
	foreach ($values as $value) {
		$temp = getTagsPerVid($value);
		// $arr = array(
			// "id" => $value
			// ,"tag" => $temp["rating"]
			// ,"count" => $temp["countPerRate"]
		
		// );
		// echo json_encode($temp);
		$ratingPerVid[$value] = $temp;
		// array_push($ratingPerVid,$arr);
	}
	// print_r($ratingPerVid[0]);
	echo json_encode($ratingPerVid);
}
else if(isset($request->all)){
	echo json_encode(getLocalVideos());
}
else if(isset($request->projectId)){
	$projectId = $request->projectId;	
	if(isset($request->filtered))
		echo json_encode(getProjectRating_filtered($projectId));
	else
		echo json_encode(getProjectRating($projectId));
}
/*
else if(isset($request->addRating)){
	$values = $request->ratingArr;
	$values =  (array)$values[0]; // cast to array
	if(insert_ratings('tbl_rating',$values))
		echo 'true';
	else 
		echo 'false';
}
else if(isset($request->editRating)){
	$values = $request->ratingArr;
	$values =  (array)$values[0]; // cast to array
	// var_dump($values);
	if(update_rating('tbl_rating',$values))
		echo 'true';
	else 
		echo 'false';
}
else if(isset($request->deleteRating)){
	$values = $request->ratingId;	
	if(deactivate_rating('tbl_rating',$values))
		echo 'true';
	else 
		echo 'false';
}
else if(isset($request->activateRating)){
	$values = $request->ratingId;	
	if(activate_rating('tbl_rating',$values))
		echo 'true';
	else 
		echo 'false';
}
*/
mysql_error();
?>