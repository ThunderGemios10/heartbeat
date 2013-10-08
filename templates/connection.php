<?php
$host="localhost"; // Host name 
$username="anytv_robert"; // Mysql username 
$password="=}D3]*LT3^%@2"; // Mysql password 
$db_name="anytv_videotracker"; // Database name 

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
?>