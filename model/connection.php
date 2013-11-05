<?php
$host="localhost"; // Host name 
<<<<<<< HEAD
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="vidtracker_db"; // Database name 
=======
$username="anytv_robert"; // Mysql username 
$password="~[TA+U=l!v1q"; // Mysql password 
$db_name="anytv_heartbeat_db"; // Database name 
>>>>>>> 07575c219acf5b8bbebb383f801840f1f460a0a5

// Connect to server and select database.
// echo $username.$password;
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
// new mongo('mongodb://<dbuser>:<dbpassword>@ds041178.mongolab.com:41178/heartbeat_db)

$dbname = 'yank_db';  
$m = new MongoClient();
$db = $m->selectDB($dbname);
?>	
