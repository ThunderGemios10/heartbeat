<?php

// date_default_timezone_set('UTC');
// $current_date = date('d/m/Y == H:i:s');
// $folder = $current_date;

// $fileName = $_FILES["upload"]["name"]; 
// $fileTmpLoc = $_FILES["upload"]["tmp_name"];

// $pathAndName = "../uploads/csv/".clean($current_date)."-".$fileName;

// $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
// // Evaluate the value returned from the function if needed
// if ($moveResult == true) {
// 	$file_handle = fopen($pathAndName, "r");
// 	$convertedData = array();
// 	while (!feof($file_handle)&&(($line = fgetcsv($file_handle)) !== FALSE)) { 
// 	   // $lines = fgetcsv($file_handle);
	   
// 	   // $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/',$lines));
// 	   array_push($convertedData, $line);
// 	   // echo $lines;//trim($lines).',';
// 	}
// 	fclose($file_handle);
// 	echo json_encode($convertedData);
// }
	
if (isset($_POST["filename"])) {
	date_default_timezone_set('UTC');
    $current_date = date('d/m/Y == H:i(worry)');
    var_dump($_FILES);
	$file = $_FILES['file']["name"];
    $fileTmpLoc = $_FILES["file"]["tmp_name"];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $pathAndName = "../uploads/groupBanner/".$_POST["filename"].".".$ext;

    $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
    // Evaluate the value returned from the function if needed
    if ($moveResult == true) 
    {
        $srcsize = getimagesize($pathAndName);
    
        $arr = Array();
        $arr['filename'] = $_POST["filename"];
        $arr['width'] = $srcsize[0];
        $arr['height'] = $srcsize[1];
        
        echo json_encode($arr);
    }
    else
    {
          echo "ERROR: File not moved correctly";
    }
}
else {
	echo false;
}

    



?>
