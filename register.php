<?php 
// Starting a session
session_start();

// If user is logged it redirect to index
if( isset($_SESSION['user_id']) ){
	header("Location: index.php");
}

require 'database.php';

// Empty message string
$message = '';

// Prepared statement to prevent SQL injection
$friend_stmt = $conn->prepare("SELECT username FROM users where username = :username");
$friend_stmt->bindParam(':username', $_POST['username']);
$friend_stmt->execute();

// Check if username is taken
if ($friend_stmt->rowCount() > 0 && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])): 
	$message = 'Username is taken';

// Check if username, password, confirm password are not empty and check for regular expression to prevent XSS
elseif(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && preg_match('/^[A-Za-z][A-Za-z0-9]{0,31}$/', $_POST['username'])): 
	
	// Check if password and confirm password are the same
	if($_POST['confirm_password'] == $_POST['password']): 
		$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':username', $_POST['username']);
		// Hashing the password with bcrypt
		$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT) ); 
		// Execute prepared statement
		if($stmt->execute() ):
			$message = 'Successfully created new user';
		else:
			$message = 'Could not create new user';
		endif;
	else:
		$message = 'Passwords do not match';
	endif;

// After submitting
elseif(isset($_POST['submit'])): 
	// Check if username or password is blank
	if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['conf']) || !preg_match('/^[A-Za-z][A-Za-z0-9]{0,31}$/', $_POST['username'])): 
		$message = 'All fields are required';
	endif;

endif;
	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register Here</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>
<body>

<div id="container">	
	<div class="header">
		<h1><a href="index.php">AllActive</a></h1>
	</div> <!-- End Header -->
	
	<div id ="mainalt">
		<div id ="message">
		<?php if(!empty($message)): ?>
			<br />
			<TABLE>
				<tr>
			<td><?= $message ?></td>
				</tr>
			</TABLE>
		<?php endif; ?>
		</div> <!-- End Message -->

		<h2>Register</h2>
		<span>or <a href="login.php">login here</a></span>

		<form action="register.php" method="POST">
			
			<input type="text" placeholder="Enter a username" name="username">
			<input type="password" placeholder="Enter a password" name="password">
			<input type="password" placeholder="Confirm your password" name="confirm_password">

			<input type="submit" name="submit" value="Register">
		</form>

	</div> <!-- End Main -->

</div> <!-- End Container -->

</body>
</html>