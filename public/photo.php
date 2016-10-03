<?php
	require_once("../includes/initialize.php");

	if(empty($_GET['id'])){
		$session->message("No photograph ID was provided.");
		header('location: index.php');
	}
	// find photo by id
	$photo = Photograph::find_by_id($_GET['id']);
	if(!$photo){
		$session->message('The photo could not be located.');
		header('location: index.php');
	}

	if(isset($_POST['submit'])){
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);
		$new_comment = Comment::make($photo->id,$author,$body);
		if($new_comment && $new_comment->save()){
			// comment saved
			// no message needed; seeing the comment is proof enough
			$new_comment->try_to_send_notification();
			header("location: photo.php?id=".$photo->id);
		}
		else{
			// failed
			$message = "There was an error that prevented the comment from being saved.";
		}
	}
	else{
		$author = '';
		$body = '';
	}

	$comments = $photo->comments();
?>
<?php include_layout_template('header.php'); ?>
	<a href="index.php">&laquo; Back</a><br><br>
	<div style="margin-left: 20px;">
		<img src="<?php echo $photo->image_path(); ?>" alt="<?php echo $photo->filename; ?>">
		<p><?php echo $photo->caption; ?></p>
	</div>

	<div>
		<?php foreach($comments as $comment): ?>
			<div class="panel panel-default" style="margin-bottom:2em;" >
				<div class="panel-body">
					<div class="author">
						<?php echo htmlentities($comment->author); ?> wrote
					</div>
					<div class="body">
						<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
					</div>
					<div class="meta-info" style="font-size: 0.8em;">
						<?php echo datetime_to_text($comment->created); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($comments)) {echo "No Comments.";} ?>
	</div>

	<div>
		<h3>New Comment</h3>
		<?php echo output_message($message); ?>
		<form action="photo.php?id=<?php echo $photo->id; ?>" method="POST" role="form">
			<table>
				<div class="form-group">
					<label for="">Your name:</label>
					<input type="text" name="author" value="<?php echo $author; ?>" class="form-control" placeholder="name">
				</div>
				<div class="form-group">
					<label for="">Your comment:</label>
					<textarea name="body" cols="40" rows="10" class="form-control" placeholder="comment"><?php echo $body; ?></textarea>
				</div>
			</table>
			
			<button type="submit" class="btn btn-primary" name="submit">Submit Comment</button>
		</form>
	</div>

	<br><br><br>
<?php include_layout_template('footer.php'); ?>