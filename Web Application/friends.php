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
	<title>AllActive - Friends</title>
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
			<a class="active" href="friends.php">Friends</a>
			<a href="members.php">Members</a>
			<a href="help.php">Help</a>
			<a style="float:right" href="logout.php">Logout</a>
		</div> <!-- End Nav -->
		
		<div id ="mainalt">
			<br /><br /><h3>Friends</h3>
			<?php 
				$current_id = $_SESSION['user_id'];
				$friends_query = $conn->prepare("SELECT user_a, user_b FROM friends WHERE user_a='$current_id' OR user_b='$current_id'");
				$friends_query->execute();

				if($friends_query->rowCount() > 0) {
					while ($run_friends = $friends_query->fetch(PDO::FETCH_ASSOC)) {
						$user_a = $run_friends['user_a'];
						$user_b = $run_friends['user_b'];

						// If user_a is current_id then it will display user_b name
						if ($user_a == $current_id) { 
							$user = $user_b;
						// Else show user_a name
						} else { 
							$user = $user_a;
						}
						$username = getUser($user, 'username');
						echo "<a href='profile.php?user=$user' class='list' style='display:block'>$username</a>";
					}
				} else {
					echo "You have no friends :(";
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