<?php  
	require_once("../../includes/initialize.php");
	if(!$session->is_logged_in()) {header("location: login.php");}

	// must have an ID
	if(empty($_GET['photo_id'])){
		$session->message("No photograph ID was provided.");
		header('location: index.php');
	}

	$photo = Photograph::find_by_id($_GET['photo_id']);
	$f=$photo->destroy();
	if($photo && $f){
		$session->message("The photo ".$photo->filename." was deleted.");
		header('location: list_photos.php');
	}
	else{
		$m='0';
		if(!empty($photo)) $m .= '__AA__';
		if(!empty($f)) $m .= '__BB__';
		$session->message('The photo could not be deleted.'.$m);
		header('location: list_photos.php');
	}

	if(isset($database)) {$database->close_connection();}
?>