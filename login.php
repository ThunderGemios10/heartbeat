<?php 
session_start();
require 'apirequest/include/oauth_instance.php';
require 'model/connection.php';

$collectionChannel = new MongoCollection($db, 'channel');

if(isset($_GET["error"])){
	if($_GET["error"]=="access_denied")
	{
		// header("location: index.php");
	}
}
else {
	if(!isset($_SESSION['token'])) {		
		$client->authenticate();
		$_SESSION['token'] = $client->getAccessToken();	
	}
	
	if ($client->getAccessToken()) {
		// header("location: index.php");
		$client->setAccessToken($_SESSION['token']);	
		if(!(isset($_SESSION["userinfo"]))) {
			$userinfo = $clientInfo->userinfo;
			$_SESSION["userinfo"] = $userinfo->get();
			
			$useremail = $_SESSION["userinfo"]["email"];	
		
			/**********************GET CHANNEL**************************/
			$channelsResponse = $youtube->channels->listChannels('id,snippet,contentDetails', array(
	          	'mine' => 'true'));
			$channelId = $channelsResponse['items'][0]['id'];
			$_SESSION["currentUserChannelInfo"] = $channelsResponse;
			$_SESSION["channelsResponse"] = $channelsResponse;
			$_SESSION["channelId"] = $channelId;
	        $_SESSION["playlistId"] = $channelsResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
	        $_SESSION["channelInfo"] = $channelsResponse['items'][0];
	        $_SESSION["thumbnail"] = $channelsResponse['items'][0]['snippet']['thumbnails']['default']['url'];
	   		/**********************GET CHANNEL**************************/
	       			
	   		$contents = file_get_contents('https://gdata.youtube.com/feeds/api/users/'.$_SESSION['channelId'].'?alt=json');
			$decodedContents = json_decode($contents);
			$_SESSION["level1"] = (array) $decodedContents->entry;
			$_SESSION["level2"] = (array) $_SESSION["level1"]['yt$username'];
			$_SESSION["level3"] = $_SESSION["level2"]['$t'];
			$_SESSION["channelUsername"] =  $_SESSION["level3"];

			$collectionAuthuser = new MongoCollection($db, 'authuser');			
			$cursor = $collectionAuthuser->find(array(
				'authemail' => $useremail,
				'$or' => array(
					array('authtype' => array('$in' => array('user', 'admin')))					
				),
			));
			if($cursor->hasNext()) {
				$convertedObj = array();
				foreach ($cursor as $doc) {
					array_push($convertedObj,$doc);
				}
				$_SESSION["valid"] = true;
				$_SESSION["userlevel"] = $convertedObj[0]["authtype"];
				$_SESSION["userId"] = $convertedObj[0]["_id"];
				header("location: home.php");
			}
			else {
				$insert = array(
					"authname"=> $_SESSION["userinfo"]["name"]
					,"project"=> 'default'
					,"authtype"=> 'user'
					,"authemail"=> $useremail
					,"userinfo"=> $_SESSION["userinfo"]
				);
				$collectionAuthuser->insert($insert);
				// $_SESSION["puto"] = $insert;
				$_SESSION["valid"] = true;
				$_SESSION["userlevel"] = 'user';
				$_SESSION["userId"] = $insert["_id"];
				header("location: home.php");
				// var_dump($insert);
				// $userdomain = $_SESSION["userinfo"]["hd"];
				// $cursor = $collectionAuthuser->find(array(
				// 									'authemail' => $userdomain,
				// 									'$and' => array(
				// 										array('authtype' => 'domain')					
				// 									),
				// 								));
				// if($cursor->hasNext()) {
				// 	$convertedObj = array();
				// 	foreach ($cursor as $doc) {
				// 		array_push($convertedObj,$doc);
				// 	}
				// 	$_SESSION["valid"] = true;
				// 	$_SESSION["userlevel"] = 'guest';
				// 	$_SESSION["userId"] = $convertedObj[0]["_id"];
				// 	header('Location: home.php');					
				// }
				// else {					
				// 	$cursor = $collectionAuthuser->find(array(
				// 					'authemail' => 'gmail.com',
				// 					'$and' => array(
				// 						array('authtype' => 'domain')					
				// 					),
				// 				));
				// 	$convertedObj = array();
				// 	foreach ($cursor as $doc) {
				// 		array_push($convertedObj,$doc);
				// 	}
				// 	if($convertedObj)  {
				// 		$_SESSION["valid"] = true;
				// 		$_SESSION["userlevel"] = 'guest';
				// 		header('Location: home.php');
				// 	}					
				// 	else {

				// 		unset($_SESSION["state"]);
				// 		unset($_SESSION["token"]);
				// 		unset($_SESSION["userinfo"]);
				// 		unset($_SESSION["valid"]);
				// 		unset($_SESSION["userlevel"]);
				// 		header('Location: error.php?invalid'.$userdomain);
				// 	}
				// }				
			}
		}
		else {
			$state = mt_rand();
			$client->setState($state);
			$_SESSION['state'] = $state;
			$authUrl = $client->createAuthUrl();
			// header( 'Location: '.$authUrl);		
		}		
	}
	else
	{	
		if(isset($_SESSION["userinfo"])) {
			unset($_SESSION["state"]);
			unset($_SESSION["token"]);
			unset($_SESSION["userinfo"]);
			unset($_SESSION["valid"]);
			// unset($_SESSION["userlevel"]);
			// header("location: login.php");
		}
		if(isset($_SESSION["valid"])){
			unset($_SESSION["token"]);
			// var_dump($_SESSION["token"]);
			// header("location: login.php");
		}
		
		//
	}
}
?>

