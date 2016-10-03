<?php  

	require_once("../../includes/initialize.php");

	$message = "";

	if($session->is_logged_in()){
		header("location: index.php");
	}

	if(isset($_POST['submit'])){
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		// Check database to see if username/password exist
		$found_user = User::authenticate($username,$password);

		if($found_user){
			$session->login($found_user);
			header("location:index.php");
		}
		else{
			$message = "username/password incorrect!";
		}
	}
	else{
		// Form has not been submitted
		$username = "";
		$password = "";
	}

?>


<html>
	<head>
		<title>Photo Gallery</title>
		<!-- Bootstrap CSS -->
		<link href="../stylesheets/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div> <h1>Photo Gallery</h1> </div>
			<div> 
				<h2>Staff Login</h2> 
				<?php echo output_message($message); ?>

				<form action="login.php" method="POST" role="form">
					<legend>Login</legend>
				
					<div class="form-group">
						<label>Username:</label>
						<input name="username" type="text" class="form-control" placeholder="username" value="<?php echo htmlentities($username); ?>" maxlength="30">
					</div>

					<div class="form-group">
						<label>Password:</label>
						<input name="password" type="password" class="form-control" placeholder="password" value="<?php echo htmlentities($password); ?>" maxlength="30">
					</div>
				
					<button name="submit" type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
			<div> Copyright <?php echo date("Y", time()); ?>, Muhammad Rabiul Alam </div>
		</div>
	</body>
</html>

<?php if(isset($database)) {$database->close_connection();} ?>