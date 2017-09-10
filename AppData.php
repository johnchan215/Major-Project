<?php 
session_start();

require 'database.php';

$response = array();

$steps = $_POST['steps'];
$floors = $_POST['floors'];
$distance = $_POST['distance'];
$calories = $_POST['calories'];
$current_id = $_SESSION['user_id'];


$records = $conn->prepare("SELECT COUNT(*) FROM api_info WHERE user='$current_id'");
$records->execute();
$num_rows = $records->fetchColumn();

if($num_rows == 1) {
	$response["status"] = "Success";
	$response["message"] = "Activity data is updated";
	$conn->query("DELETE FROM api_info WHERE user='$current_id'");
	$conn->query("INSERT INTO api_info VALUES('', '$steps', '$floors', '$distance', '$calories', '$current_id')");

} else if ($num_rows == 0) {
	$response["status"] = "Success";
	$response["message"] = "Activity data is created";
	$conn->query("INSERT INTO api_info VALUES('', '$steps', '$floors', '$distance', '$calories', '$current_id')");
	
} else {
	$response["status"] = "error";
	$response["message"] = "User is not found";
}

echo json_encode($response);

 ?>