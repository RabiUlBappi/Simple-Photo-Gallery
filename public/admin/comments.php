<?php
	require_once("../../includes/initialize.php");

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

	$comments = $photo->comments();
?>
<?php include_layout_template('admin_header.php'); ?>
	<a href="list_photos.php">&laquo; Back</a><br><br>
	<h2>Comments on <?php echo $photo->filename; ?></h2>
	<?php echo output_message($message); ?>
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
					<div style="font-size: 0.8em;">
						<a href="delete_comment.php?id=<?php echo $comment->id; ?>">Delete Comment</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($comments)) {echo "No Comments.";} ?>
	</div>

	

	<br><br><br>
<?php include_layout_template('admin_footer.php'); ?>