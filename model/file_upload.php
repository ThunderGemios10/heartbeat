<?php

date_default_timezone_set('UTC');
$current_date = date('d/m/Y == H:i:s');
$folder = $current_date;



if(file_exists("../uploads/csv")) {

}
else {
	mkdir("../uploads/csv");
}
$fileName = $_FILES["upload"]["name"]; 
$fileTmpLoc = $_FILES["upload"]["tmp_name"];

$pathAndName = "../uploads/csv/".clean($current_date)."-".$fileName;

$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
// Evaluate the value returned from the function if needed
if ($moveResult == true) {
	$file_handle = fopen($pathAndName, "r");
	$convertedData = array();
	while (!feof($file_handle)&&(($line = fgetcsv($file_handle)) !== FALSE)) { 
	   // $lines = fgetcsv($file_handle);
	   
	   // $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/',$lines));
	   array_push($convertedData, $line);
	   // echo $lines;//trim($lines).',';
	}
	fclose($file_handle);
	echo json_encode($convertedData);
}
else
{
     // echo "ERROR: File not moved correctly";
}

function clean($string) {
   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
?>