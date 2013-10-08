<?php
session_start();
// $dbhost = 'localhost';  
require 'connection.php';

$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);
$useremail = $_SESSION["userinfo"]["email"];

$collectionAuthuser = new MongoCollection($db, 'authuser');


function getAllAuthUser() {
	global $collectionAuthuser;
	
	$cursor = $collectionAuthuser->find();
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
		// echo '<hr/><pre>' . print_r($doc,true) . "</pre>"; 		
	}
	return $convertedObj;
}
function saveEditUser($id,$row) {
	global $collectionAuthuser;	
	$cursor = $collectionAuthuser->update(
		array(	//condition
			'_id'=> new MongoId($id)	
		)
		,array(	//modify
			 '$set'=> array(
			 	"authemail" => $row->authemail
			 	,"authname" =>  $row->authname
			 	,"authtype" => $row->authtype
			 	,"project" => $row->project
			 	,"status" =>  1
			 	
			 )
		)
	);
	// var_dump($cursor);
	if(isset($cursor["updatedExisting"]))
		if($cursor["updatedExisting"])	
			return "true";
	return "false";
}
function saveNewUser($row) {
	global $collectionAuthuser;	
	$cursor = $collectionAuthuser->save($row);
	if(isset($cursor["updatedExisting"]))
		if($cursor["updatedExisting"])	
			return "true";
	return "false";
}
function getAuthUserByEmail($email){
	global $collectionAuthuser;	
	$cursor = $collectionAuthuser->find(
		array(
			"authemail"=>$email
		)
	);
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);	
	}
	return $convertedObj;
}

if(isset($request->getAllAuthUser)){
	echo json_encode(getAllAuthUser(),true);
}
else if(isset($request->saveEdit)){
	$id = $request->id;
	$value = $request->editRow;
	echo saveEditUser($id,$value);
}
else if(isset($request->save)){	
	$value = $request->newRow;
	echo saveNewUser($value);
}
else if(isset($request->getAuthUserByEmail)){	
	$value = $request->email;
	echo getAuthUserByEmail($value);
}
mysql_error();
?>