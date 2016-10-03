<?php  
	require_once("../../includes/initialize.php");
	if(!$session->is_logged_in()) {header("location: login.php");}

	// must have an ID
	if(empty($_GET['id'])){
		$session->message("No comment ID was provided.");
		header('location: index.php');
	}

	$comment = Comment::find_by_id($_GET['id']);
	if($comment && $comment->delete()){
		$session->message("The comment was deleted.");
		header('location: comments.php?id='.$comment->photograph_id);
	}
	else{
		$session->message('The comment could not be deleted.');
		header('location: list_photos.php');
	}

	if(isset($database)) {$database->close_connection();}
?>