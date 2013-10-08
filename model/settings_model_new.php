<?php  
// Config  
require 'connection.php';

$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
$context = stream_context_create($opts);
$postdata = file_get_contents('php://input',false,$context);

$request = json_decode($postdata);
$collectionCategories = new MongoCollection($db, 'categories');

// $collectionCategories->remove();
		
function insertCategory($inserts) {
	global $collectionCategories;
	$doc = (array)$inserts;
	//CATEGORY INFO	
	$docCategory["categoryName"] = $doc["name"];
	$docCategory["description"] = $doc["description"];
	$docCategory["projectId"] = $doc["projectId"];
	$docCategory["dateCreated"] = new MongoDate();//current Date
	$docCategory["parent"] = $doc["parent"];
	$docCategory["status"] = "1";
	if($collectionCategories->save($docCategory)) return true;	
}
function updateCategory($updates) {
	global $collectionCategories;
	$doc = (array)$updates;
	var_dump($doc);
	echo (new MongoId($doc['id']));
	echo "<hr/><hr/>";
	$cursor = $collectionCategories->findOne(array('_id' => new MongoId($doc['id'])));
	$convertedObj = (array)$cursor;
	
	if($collectionCategories->findOne(array('_id' => new MongoId($doc['id'])))){
		if($collectionCategories->update(array('_id' => new MongoId($doc['id'])), array(
			'$set'=>array(
				'categoryName' => $doc['name']
				,'description' => $doc['desc']
				,'parent' => $doc['parent']
			)
		)))
		return "true";
	}
	return "false";
}
function activateCategory($updates) {
	global $collectionCategories;
	$doc = (array)$updates;
	$cursor = $collectionCategories->findOne(array('_id' => new MongoId($updates)));
	echo (array)$cursor;
	if($collectionCategories->findOne(array('_id' => new MongoId($updates)))){
		if($collectionCategories->update(array('_id' => new MongoId($updates)), array(
			'$set'=>array(
				'status'=> 1				
			)
		))) return "true";
	}
}
function deactivateCategory($updates) {
	global $collectionCategories;
	$doc = (array)$updates;
	$cursor = $collectionCategories->findOne(array('_id' => new MongoId($updates)));
	echo (array)$cursor;
	if($collectionCategories->findOne(array('_id' => new MongoId($updates)))){
		if($collectionCategories->update(array('_id' => new MongoId($updates)), array(
			'$set'=>array(
				'status'=> 2				
			)
		))) return "true";
	}
	// if($collectionCategories->save($docCategory)) return true;
}
function getCategories() {
	global $collectionCategories;
	$cursor = $collectionCategories->find();
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
	}
	return array_reverse($convertedObj);
}

if(isset($request->addRating)){
	$values = $request->categoryArr;
	echo insertCategory($values[0]);
}
else if(isset($request->editRating)){
	$values = $request->categoryArr;
	$values =  (array)$values[0]; // cast to array
	// var_dump($values);
	if(updateCategory($values))
		echo 'true';
	else 
		echo 'false';
}
else if(isset($request->deleteRating)){
	$values = $request->ratingId;
	echo deactivateCategory($values);
	// if(deactivateCategory($values))
		// echo 'true';
	// else 
		// echo 'false';
}
else if(isset($request->activateRating)){
	$values = $request->ratingId;
	if(activateCategory($values))
		echo 'true';
	else 
		echo 'false';
}
else if(isset($request->get)){
	echo json_encode(getCategories());
}
else {
	// echo date('Y-M-d h:i:s', 1375858861);
	$i = 1;
	$cursor = $collectionCategories->findOne(array('_id' => new MongoId('520b54e5fc30e5740b000002')));
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
		echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
		$i++;		
	}
	echo "<hr/>";
	echo "<hr/>";
	echo "<hr/>";
	$i = 1;
	$cursor = $collectionCategories->find();
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
		echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
		$i++;		
	}
	
}

?>