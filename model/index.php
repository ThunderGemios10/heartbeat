<?php  
// Config  
$dbhost = 'localhost';  
$dbname = 'yank_db';  

$m = new MongoClient();
$db = $m->selectDB($dbname);
$collectionVideo = new MongoCollection($db, 'videos');
$collectionVidStat = new MongoCollection($db, 'videoStats');
$collectionAuthUser = new MongoCollection($db, 'authuser');
$collectionCategories = new MongoCollection($db, 'categories');

// $collectionVideo->remove();
// $collectionVideo->remove(array('_id' => new MongoId('52031eabfc30e5680d00000c')));
	
$i = 1;

$cursor = $collectionVideo->find();
$convertedObj = array();
foreach ($cursor as $doc) {
	array_push($convertedObj,$doc);
	echo '<hr/>'.$i.'<pre>' . print_r($doc,true) . "</pre>"; 
	$i++;		
}	
?>