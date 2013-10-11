<?php
// Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_YouTubeService.php';
require_once 'google-api-php-client/src/contrib/Google_Oauth2Service.php';

$OAUTH2_CLIENT_ID = '734125092400.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'kr5bC6iu4SQCOnt47aF2zGIn';
$DEVELOPER_KEY = 'AIzaSyBMZjhXBUArLrLMwsfrltMMi-9RBhHMnA4';


$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = filter_var('http://demo.any.tv/mnldev/videotrackerv6/login.php');
$client->setRedirectUri($redirect);
$client->setDeveloperKey($DEVELOPER_KEY);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
$clientInfo = new Google_Oauth2Service($client);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/youtube.readonly'));

$youtube = new Google_YoutubeService($client);
?>