<?php 
// Starting a session
session_start();

require 'database.php';
require 'functions.php';

// If the user_id is set then there is a current user
if( isset($_SESSION['user_id']) ){

	// If the ID is the same as the user's ID
	$records = $conn->prepare('SELECT * FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	
	$user = NULL;
	// If user is found, then set the user as the results
	if( count($results) > 0){
		$user = $results;
	}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>AllActive</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>
<body>

<div id="container">
	<div class="header">
		<h1><a href="index.php">AllActive</a></h1>
	</div> <!-- End Header -->
	
	<?php if( !empty($user) ): ?> <!-- User is logged in -->
	
		<div class="nav">
				<a href="index.php">Home</a>
				<a href="profile.php">Profile</a>
				<a href="request.php">Requests</a>
				<a href="friends.php">Friends</a>
				<a href="members.php">Members</a>
				<a class="active" href="help.php">Help</a>
				<a style="float: right" href="logout.php">Logout</a>
		</div> <!-- End Nav -->

		<div id ="mainalt">
		<br /><br />
		<strong>Home</strong>
		<p>The homepage you can see your activity data and the steps your friends have taken</p>
		<br />
		<strong>Profile</strong>
		<p>The profile page displays your username and displays your activity data in a list format</p>
		<br />
		<strong>Requests</strong>
		<p>The request page displays all your friend requests where you can either accept or ignore</p>
		<br />
		<strong>Friends</strong>
		<p>The friends page displays all your friends and can see their steps per day</p>
		<br />
		<strong>Members</strong>
		<p>The members page displays all the registered users in the application that can be added as a friend</p>

		
		</div> <!-- End Main -->

	<?php else: ?> <!-- User is not logged in -->

		<h2>Please login or register first</h2>
		<a href="login.php">Login</a> or
		<a href="register.php">Register</a>
		
	<?php endif; ?>

	
</div> <!-- End Container -->

</body>
</html>