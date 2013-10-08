<?php
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="vidtracker_db"; // Database name 

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
// new mongo('mongodb://<dbuser>:<dbpassword>@ds041178.mongolab.com:41178/heartbeat_db)

$dbname = 'yank_db';  
$m = new MongoClient();
$db = $m->selectDB($dbname);
?>	