<?php
session_start();
if(!(isset($_SESSION["valid"]))) {
		// header("location: error.php");
	
	require "index-notsignedin.php";
}
else {
	// $_SESSION["guest"]=true;
	require 'index-signedin.php';
}
// echo $_SESSION["valid"];
// exit
?>
