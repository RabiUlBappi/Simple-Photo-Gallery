<?php  
	require_once("../../includes/initialize.php");
	if(!$session->is_logged_in()) {header("location: login.php");}
?>

<?php  

	$max_file_size = 10485760;

	if(isset($_POST['submit'])){
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		$photo->attach_file($_FILES['file_upload']);
		if($photo->save()){
			$session->message("Photograph uploaded successfully!");
			header('location: list_photos.php');
		}
		else{
			$message = join("<br>",$photo->errors);
		}
	}

?>

<?php include_layout_template('admin_header.php'); ?>
	<h2> Upload </h2>
	<?php echo output_message($message); ?>
	<form action="photo_upload.php" enctype="multipart/form-data" method="POST" role="form">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">

		<div class="form-group">
			<input type="file" class="form-control" name="file_upload">
		</div>

		<div class="form-group">
			<label for="">Caption:</label>
			<input type="text" class="form-control" name="caption" placeholder="caption">
		</div>
	
		<button type="submit" name="submit" class="btn btn-primary">Upload</button>
	</form>
<?php include_layout_template('admin_footer.php'); ?>