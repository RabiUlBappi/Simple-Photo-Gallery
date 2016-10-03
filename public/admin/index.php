<?php  
	require_once("../../includes/initialize.php");
	if(!$session->is_logged_in()) {header("location: login.php");}
?>
<?php include_layout_template('admin_header.php'); ?>
	<h2>Menu</h2>
	<?php echo output_message($message); ?>
	<ul>
		<li><a href="logout.php">Logout</a></li>
		<li><a href="list_photos.php">List Photo</a></li>
	</ul>
<?php include_layout_template('admin_footer.php'); ?>