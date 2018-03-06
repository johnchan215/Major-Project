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
	if( count($results) > 0) {
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
				<a class="active" href="index.php">Home</a>
				<a href="profile.php">Profile</a>
				<a href="request.php">Requests</a>
				<a href="friends.php">Friends</a>
				<a href="members.php">Members</a>
				<a href="help.php">Help</a>
				<a style="float: right" href="logout.php">Logout</a>
		</div> <!-- End Nav -->

		<div id ="main">
			<br /><br />Welcome <strong><?= $user['username']; ?></strong>
			<br />You have successfully logged in!
			
			<form method="GET" action="login_with_fitbit2.php">
			<br /><input type="submit" value="Sync to Fitbit">

		<TABLE>
			<tr>
			<?php 
			// Gather personal data from database of current user
			$current_id = $_SESSION['user_id'];
			$request_query = $conn->query("SELECT * FROM api_info WHERE user='$current_id'");
			
			// If there is a result, then print the data
			if($request_query->rowCount() > 0) {	
				foreach ($request_query as $row) {
					echo '<td><strong><img src="assets/img/steps.png" alt="steps" /><br />Steps: </strong>', print_r($row['steps'],1),'</td>';
					echo '<td><strong><img src="assets/img/stairs.png" alt="stairs" /><br />Floors: </strong>', print_r($row['floors'],1),'</td></tr>';
					echo '<tr><td><strong><img src="assets/img/distance.png" alt="distance" /><br />Distance: </strong>', print_r($row['distance'],1),' km</td>';
					echo '<td><strong><img src="assets/img/calories.png" alt="calories" /><br />Calories: </strong>', print_r($row['calories'],1),' kcal</td>';
				}
			// If there is NO result, then prin them as 0
			} else {
				echo '<td><strong><img src="assets/img/steps.png" alt="steps" /><br />Steps: </strong>0</td>';
				echo '<td><strong><img src="assets/img/stairs.png" alt="stairs" /><br />Floors: </strong>0</td></tr>';
				echo '<tr><td><strong><img src="assets/img/distance.png" alt="distance" /><br />Distance: </strong>0 km</td>';
				echo '<td><strong><img src="assets/img/calories.png" alt="calories" /><br />Calories: </strong>0 kcal</td>';
			}
			?>
			
			</tr>
		</TABLE>
		
		</div> <!-- End Main -->

		<div id= "friends">
			<TABLE>
			<br /><br />
			<thead>
				<tr>
					<th colspan="2">Friends</th>
				</tr>
				<tr>
					<th>Username</th>
					<th>Steps</th>
				</tr>
			</thead>
			<tbody>

			<?php // Gather friends
				// Select friends where logged in user ID is in the friends table
				$friends_query = $conn->prepare("SELECT user_a, user_b FROM friends WHERE user_a='$current_id' OR user_b='$current_id'");
				$friends_query->execute();

				while ($run_friends = $friends_query->fetch(PDO::FETCH_ASSOC)) {
					$user_a = $run_friends['user_a'];
					$user_b = $run_friends['user_b'];
					if ($user_a == $current_id) { // If user_a is current_id then it will display user_b name
						$user = $user_b;
					} else { // Else show user_a name
						$user = $user_a;
					}
					$username = getUser($user, 'username');
					echo "<tr><td><a href='profile.php?user=$user' class='list' style='display:block'>$username</a></td>";
					
					$friend_steps = $conn->query("SELECT steps FROM api_info WHERE user='$user'");
					if($friend_steps->rowCount() > 0) {
						// Print steps of friend user
						foreach ($friend_steps as $row) {
							echo '<td>', print_r($row['steps'],1),'</td></tr>';
						}
					// Print 0 steps if there is no data
					} else {
						echo '<td>0</td></tr>';
					}
				}
			?>
			</tbody>
			</TABLE>
		</div> <!-- End Friends -->	

	<?php else: ?> <!-- User is not logged in -->

		<h2>Please login or register first</h2>
		<a href="login.php">Login</a> or
		<a href="register.php">Register</a>
		
	<?php endif; ?>

	
</div> <!-- End Container -->

</body>
</html>