<?php  
// Config  
session_start();
// $dbhost = 'localhost';  
$dbname = 'admin';
// $m = new MongoClient("mongodb://root:hhb5s2fk4z6H4xEh@127.0.0.1:21000/");

$m = new MongoClient("mongodb://${root}:${hhb5s2fk4z6H4xEh}@localhost:21000");
$db = $m->selectDB($dbname);
$collectionVideo = new MongoCollection($db, 'videos');

$cursor = $collectionVideo->find();
	$convertedObj = array();
	foreach ($cursor as $doc) {
		array_push($convertedObj,$doc);
		echo '<hr/><pre>' . print_r($doc,true) . "</pre>";
	}

	var_dump($convertedObj);
	echo "<hr/><hr/>";	
	echo "<hr/><br/>";
?>