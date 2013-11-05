<?php

$host="localhost"; // Host name 
$username="anytv_robert"; // Mysql username 
$password="~[TA+U=l!v1q"; // Mysql password 
$db_name="anytv_heartbeat_db"; // Database name 

// Connect to server and select database.
// echo $username.$password;
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

$dbname = 'heartbeatdb';  

$m = new MongoClient();
$db = $m->selectDB($dbname);
?>