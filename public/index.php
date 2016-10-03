<?php
	require_once("../includes/initialize.php");

	// find all photos
	$photos = Photograph::find_all();

	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 3;
	$total_count =Photograph::count_all();

	$pagination = new Pagination($page, $per_page, $total_count);

	$sql  = " SELECT * FROM photographs ";
	$sql .= " LIMIT ".$per_page;
	$sql .= " OFFSET ".$pagination->offset();
	$photos = Photograph::find_by_sql($sql);
?>
<?php include_layout_template('header.php'); ?>
	<?php foreach($photos as $photo): ?>
		<div style="float:left; margin-left: 20px;">
			<a href="photo.php?id=<?php echo $photo->id; ?>">
				<img src="<?php echo $photo->image_path(); ?>" alt="<?php echo $photo->filename; ?>" width="200">
			</a>
			<p><?php echo $photo->caption; ?></p>
		</div>
	<?php endforeach; ?>
	<div style="clear: both">
		<?php
			if($pagination->total_pages()>1){
				echo '<ul class="pagination pager">';
				if($pagination->has_previous_page()){
					echo '<li class="previous"><a href="index.php?page='.$pagination->previous_page().'">&laquo; Previous</a></li>';
				}

				
				for($i=1; $i<=$pagination->total_pages(); $i++){
					if($i==$page){
						echo ' <li class="active"><a href="index.php?page='.$i.'">'.$i.'</a></li>';
					}
					else{
						echo '<li><a href="index.php?page='.$i.'">'.$i.'</a></li>';
					}
				}
				

				if($pagination->has_next_page()){
					echo '<li class="next"><a href="index.php?page='.$pagination->next_page().'">&raquo; Next</a></li>';
				}
				echo '</ul>';
			}
		?>
	</div>
	<br><br><br>
<?php include_layout_template('footer.php'); ?>