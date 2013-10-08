<?php
	session_start();
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	if(isset($_SESSION["valid"])) {
		if(isset($request->useremail)){
			echo json_encode($_SESSION["userinfo"]);
		}
		else if(isset($request->userlevel)){
			if(isset($_SESSION["userlevel"])) {				
				if($_SESSION["userlevel"]=='admin'){
					echo 'true';
				}
				else{
					echo 'false';
				}
			}
		}

		else if(isset($request->playlistId)){
			echo json_encode($_SESSION["playlistId"]);
		}
		else if(isset($request->channel)){
			echo json_encode($_SESSION["channelInfo"],JSON_NUMERIC_CHECK);
		}
		else if(isset($request->thumbnail)){
			echo json_encode($_SESSION["thumbnail"],JSON_NUMERIC_CHECK);
		}
		else if(isset($request->key)){
			$key = $request->key;
			// $value = $request->value;
			echo json_encode($_SESSION[$key],JSON_NUMERIC_CHECK);
		}
	}


?>