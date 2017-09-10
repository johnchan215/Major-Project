<?php 
// Starting a session
session_start();

require 'functions.php';
require 'database.php';

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
	<title>AllActive - Profile</title>
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
			<a class="active" href="profile.php">Profile</a>
			<a href="request.php">Requests</a>
			<a href="friends.php">Friends</a>
			<a href="members.php">Members</a>
			<a href="help.php">Help</a>
			<a style="float:right" href="logout.php">Logout</a>
		</div> <!-- End Nav -->

		<?php

			// If there is a user and not empty
			if(isset($_GET['user']) && !empty($_GET['user']) ) {
				$user = $_GET['user']; // target user id
			// If user does not exist, then redirect back to user's account
			} else {
				$user = $_SESSION['user_id']; // current user id
			}

			$current_id = $_SESSION['user_id'];
			$username = getUser($user, 'username');

		?>
		<div id="mainalt">
			<br /><br /><h3><?php echo $username; ?></h3>

			<?php
				// Buttons will not appear for current user profile
				if($user != $current_id) { 
					$friends_query = $conn->prepare("SELECT COUNT(*) FROM friends WHERE (user_a='$current_id' AND user_b='$user') OR (user_a='$user' AND user_b='$current_id')");
					$friends_query->execute();
					$num_rows = $friends_query->fetchColumn();

					// Buttons for user's who friended each other with an option to unfriend each other. 
					if ($num_rows == 1) {
						echo "<a href='#' class='list'>Already Friends</a> | <a href='actions.php?action=unfriend&user=$user' class='list'>Unfriend $username</a>";
						$friend_steps = $conn->query("SELECT steps FROM api_info WHERE user='$user'");
						if($friend_steps->rowCount() > 0) {
							foreach ($conn->query("SELECT steps FROM api_info WHERE user='$user'") as $row) {
								echo '<br /><p><strong>Steps: </strong>', print_r($row['steps'],1),'</p>';
							}
						} else {
							echo '<br /><p><strong>Steps: </Strong>0</p>';
						}
					} else {
						$from_query = $conn->prepare("SELECT COUNT(*) FROM friend_request WHERE sender='$user' AND receiver='$current_id'");
						$from_query->execute();
						$num_rows_from = $from_query->fetchColumn();

						$to_query = $conn->prepare("SELECT COUNT(*) FROM friend_request WHERE sender='$current_id' AND receiver='$user'");
						$to_query->execute();
						$num_rows_to = $to_query->fetchColumn();

						// Send ignore or accept friend request buttons if auser has send the current user a friend request
						if ($num_rows_from == 1) { 
							echo "<a href='actions.php?action=ignore&user=$user' class='list'>Ignore</a> | <a href='actions.php?action=accept&user=$user' class='list'>Accept</a>";
						
						// Send cancel friend request button if user has send a friend requests but wants to cancel the request
						} else if ($num_rows_to == 1) { 
							echo "<a href='actions.php?action=cancel&user=$user' class='list'>Cancel Request</a>";
						
						// Send friend request button button if user wants to send a friend request to another user
						} else { 
							echo "<a href='actions.php?action=send&user=$user' class='list'>Send Friend Request</a>";
						}
					}

				// Print activity data on the profile page of the current user	
				} else { 
					$activity_query = $conn->query("SELECT * FROM api_info WHERE user='$current_id'");

					if($activity_query->rowCount() > 0) {
						foreach ($activity_query as $row) {
							echo '<p><strong>Steps: </strong>', print_r($row['steps'],1),'</p>';
							echo '<p><strong>Floors: </strong>', print_r($row['floors'],1),'</p>';
							echo '<p><strong>Distance: </strong>', print_r($row['distance'],1),' km</p>';
							echo '<p><strong>Calories: </strong>', print_r($row['calories'],1),' kcal</p>';
						}
					} else {
						echo '<p><strong>Steps: </strong>0</p>';
						echo '<p><strong>Floors: </strong>0</p>';
						echo '<p><strong>Distance: </strong>0 km</p>';
						echo '<p><strong>Calories: </strong>0 kcal</p>';
					}
					
				}
			?>
		</div> <!-- End Main -->

	<?php else: ?> <!-- User is not logged in -->

		<h2>Please login or register first</h2>
		<a href="login.php">Login</a> or
		<a href="register.php">Register</a>

	<?php endif; ?>

</div> <!-- End Container -->

</body>
</html>