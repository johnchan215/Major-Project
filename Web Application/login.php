<?php 
// Starting a session
session_start();

// If user is logged it redirect to index
if( isset($_SESSION['user_id']) ){
	header("Location: index.php");
}

require 'database.php';

// Check if username or password fields are not empty and check for regular expressions to prevent XSS
if(!empty($_POST['username']) && !empty($_POST['password']) && preg_match('/^[A-Za-z][A-Za-z0-9]{0,31}$/', $_POST['username'])): 
	
	// Prepared statement to prevent SQL injection
	$records = $conn->prepare('SELECT * FROM users WHERE username = :username');
	$records->bindParam(':username', $_POST['username']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$message = '';

	// Check if user exists and checks if password matches the password in the database 
	// through the password_verify function
	if(count($results) > 0 && password_verify($_POST['password'], $results['password']) ) {
		$_SESSION['user_id'] = $results['id'];	

		header("Location: index.php");
		
	} else {
		$message = 'Username or password is incorrect';
	}

endif;

// After submiting
if(isset($_POST['submit']) ) {
	// Check if username or password fields are empty or check for special characters to prevent XSS
	if(empty($_POST['username']) || empty($_POST['password']) || !preg_match('/^[A-Za-z][A-Za-z0-9]{0,31}$/', $_POST['username'])) {
		$message = 'All fields are required';	
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login Here</title>
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

		<h2>Login</h2>
		<span>or <a href="register.php">register here</a></span>


		<form action="login.php" method="POST">
			
			<input type="text" placeholder="Enter your username" name="username">
			<input type="password" placeholder="Enter your password" name="password">

			<input type="submit" name="submit" value="Login">
		</form>
	</div> <!-- End Main -->

</div> <!-- End Container -->	
</body>
</html>