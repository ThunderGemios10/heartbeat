<?php
ini_set('max_execution_time', 0);
$filename="Channels";
$channels = array();
            if (($handle = fopen($filename.".csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        array_push($channels, $data[$c]);
                    }
                }
                fclose($handle);
            }
            $youtubestats = getChannelsDataV2($channels, 1);

            $fp = fopen($filename."2.csv", 'w');
            fputcsv($fp, array('Channel', 'Subscribers' ,'Views'));
            foreach ($youtubestats as  $stats) {
                fputcsv($fp, array($stats['id'], $stats['subscribers'], $stats['views']));
            }
            fclose($fp);

function getChannelsDataV2($channels, $sleep = 0){
          $channeldata = array();
          $limit = 10;
          $loops = ceil(sizeof($channels)/$limit);
          $start = 0;
          $ctr=0;
          for($i=0;$i<$loops;$i++)
          {
            $st = $i * $limit;
            $trimmed = array_slice($channels, $st,$limit);
            // echo '<hr>';
            foreach ($trimmed as $ch) {
              $vidstats = array();
              $vidstats['entry']['yt$statistics']['subscriberCount'] = 0;
              $vidstats['entry']['link'][0]['href'] ='';
              $vidstats['entry']['yt$statistics']['totalUploadViews'] = 0;
              $vidstats = json_decode(file_get_contents('https://gdata.youtube.com/feeds/api/users/'. $ch.'?alt=json'),true);
              $topush = array('id'=>end(explode('/', $vidstats['entry']['link'][0]['href'])) ,'subscribers'=>$vidstats['entry']['yt$statistics']['subscriberCount'], 'views'=>$vidstats['entry']['yt$statistics']['totalUploadViews']);
              echo $ctr++;
              var_dump($topush);
              array_push($channeldata, $topush);
              sleep($sleep);
            }
            sleep($sleep);
            // break;
          }
          // $ctr = 0;
          // foreach ($channels as $ch) {
          //   $vidstats = array();
          //   $vidstats['entry']['yt$statistics']['subscriberCount'] = 0;
          //   $vidstats['entry']['yt$statistics']['totalUploadViews'] = 0;
          //   $vidstats = json_decode(file_get_contents('https://gdata.youtube.com/feeds/api/users/'. $ch.'?alt=json'),true);
          //   array_push($channeldata, array('id'=>$ch ,'subscribers'=>$vidstats['entry']['yt$statistics']['subscriberCount'], 'views'=>$vidstats['entry']['yt$statistics']['totalUploadViews']));
          //   sleep($sleep);
          //   // $ctr++;
          //   // if($ctr == 10)
          //   // break;
          // }
          return $channeldata;
        }



?>