<?php

session_start();
require 'include/oauth_instance.php';


// if (isset($_GET['code']))
// {
	// if (strval($_SESSION['state']) !== strval($_GET['state'])) 
	// {
		// die('The session state did not match.');
	// }
	if(!isset($_SESSION['token']))
	{
		
		$client->authenticate();
		$_SESSION['token'] = $client->getAccessToken();
		
	}
// }

if (isset($_SESSION['token']))
{	
	$client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken())
{
	$_SESSION['token'] = $client->getAccessToken();
	try
	{
		
	} 
	catch (Google_ServiceException $e) {
		// echo ($e->getMessage());
		echo "Error";
	} 
	catch (Google_Exception $e) {
		// echo ($e->getMessage());
		echo "Error";
	}

	$_SESSION['token'] = $client->getAccessToken();
}
else
{
	$state = mt_rand();
	$client->setState($state);
	$_SESSION['state'] = $state;

	$authUrl = $client->createAuthUrl();
	header( 'Location: '.$authUrl) ;
}




			


?>