<?php
// http://codewithchris.com/iphone-app-connect-to-mysql-database/
session_start();

require 'database.php';

//Swift uses array in json format to communicate to the web
$response = array();

$username = $_POST['username'];
$password = $_POST['password'];

// This SQL prepared statement selects ALL from the table 'users' where username is the same as the posted username
$records = $conn->prepare("SELECT * FROM users WHERE username = :username");
$records->bindParam(':username', $username);
$records->execute();
$results = $records->fetch(PDO::FETCH_ASSOC);
   
// Count if the results are more than one and if the password verify returns as true
if(count($results) > 0 && password_verify($password, $results['password']) )
{   
	$_SESSION['user_id'] = $results['id'];
	// Returns response of Success if statement is true
    $response["status"] = "Success";
    $response["message"] = "User is registered";
    
} else {
	// Returns response of error if statement is false
    $response["status"] = "error";
    $response["message"] = "User is not found";
}

echo json_encode($response);

?>
