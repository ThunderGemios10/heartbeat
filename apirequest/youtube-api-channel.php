<?php
// require 'include/oauth_instance.php';
session_start();
$searchFinalResponse = array();            
$searchFinalResponse['items'] = array();
$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

function getVideosFromPlaylist($playlistId){
    require 'include/oauth_instance.php';
    $searchResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $playlistId,
        'maxResults' => 50,
        'fields' => 'items(snippet(publishedAt,channelId,title,description,thumbnails,resourceId)),pageInfo,nextPageToken'
    ));        
   $searchFinalResponse = array();            
    $searchFinalResponse['items'] = array();
    $temp = array();        
    $start =0;
    $limit = 50;
    $loops = $searchResponse['pageInfo']['totalResults']/$limit;        
    // var_dump($searchResponse);
    if(isset($searchResponse['nextPageToken']))
    {
        $pToken = $searchResponse['nextPageToken'];    
    }
    foreach($searchResponse['items'] as $eachData)
    {
        array_push($searchFinalResponse['items'],$eachData);
    }        
    for($i=1;$i<$loops;$i++)
    {
         $partialResult= $youtube->playlistItems->listPlaylistItems('snippet,statistics', array(
        'playlistId' => $playlistId,
        'maxResults' => 50,
        'pageToken' => $pToken,
        'fields' => 'items(snippet(publishedAt,channelId,title,description,thumbnails,resourceId)),pageInfo,nextPageToken,statistics'
        ));
        $temp[$i-1] = $partialResult['items'];
        if($i==$loops-1)
        $pToken = $temp[$i-1]['nextPageToken'];
    }
    for($i=0;$i < sizeof($temp);$i++)
    {
        foreach($temp[$i] as $eachData)
        {
           array_push($searchFinalResponse['items'],$eachData);
        }
    }
    return $searchFinalResponse;
}
function getChannel($value){
    require 'include/oauth_instance.php';
    $channelsResponse = $youtube->channels->listChannels('id,snippet,contentDetails', array(
        'id' => $value        
    ));
    return $channelsResponse;
}
function getChannelField($value,$field) {    
    require 'include/oauth_instance.php';
    $channelsResponse = $youtube->channels->listChannels($field, array(
        'forUsername' => $value
    ));
    return $channelsResponse;
}
if(isset($request->getChannelPlaylist)){
    if(isset($_SESSION["playlistId"])) {
        $playlistId = isset($_SESSION["playlistId"])?$_SESSION["playlistId"]:NULL;
        echo json_encode(getVideosFromPlaylist($playlistId),JSON_NUMERIC_CHECK);
    }
}
else if(isset($_GET["channelId"])){
    if(isset($_GET["uploads"])) {
        $channelsResponse = getChannel($_GET["channelId"]);
        $playlistId = $channelsResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
        echo json_encode(getChannel($playlistId),JSON_NUMERIC_CHECK);
    }
    else {
        // echo getVideosFromPlaylist('1');
        echo json_encode(getChannel($_GET["channelId"]),JSON_NUMERIC_CHECK);
    }
}
else if(isset($_GET["channelUsername"])){
    if(isset($_GET["field"])) {
        $temp = getChannelField($_GET["channelUsername"],$_GET["field"]);
        echo $temp["items"][0]["id"];
        // echo json_encode(getChannelField($_GET["channelUsername"],$_GET["field"]),JSON_NUMERIC_CHECK);
    }
}
else if(isset($request->channelId)){   
    $channelsResponse = getChannel($request->channelId);
    $playlistId = $channelsResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
    echo json_encode(getChannel($playlistId),JSON_NUMERIC_CHECK);
}


?>
