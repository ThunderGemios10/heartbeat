<?php
require 'include/oauth_instance.php';
session_start();
$searchFinalResponse = array();            
$searchFinalResponse['items'] = array();
$postdata = file_get_contents("php://input",true);
$request = json_decode($postdata);

if(isset($request->getChannelPlaylist)){
    if(isset($_SESSION["playlistId"])) {
        $playlistId = $_SESSION["playlistId"];

        $searchResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $playlistId,
            'maxResults' => 50,
            'fields' => 'items(snippet(publishedAt,channelId,title,description,thumbnails,resourceId)),pageInfo,nextPageToken'
        ));        
        $temp = array();        
        $start =0;
        $limit = 50;
        $loops = $searchResponse['pageInfo']['totalResults']/$limit;        
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
             $partialResult= $youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $playlistId,
            'maxResults' => 50,
            'pageToken' => $pToken,
            'fields' => 'items(snippet(publishedAt,channelId,title,description,thumbnails,resourceId)),pageInfo,nextPageToken'
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

        echo json_encode($searchFinalResponse,JSON_NUMERIC_CHECK);
    }

}
else if(isset($request->getChannelUsername)){
    
}

?>
