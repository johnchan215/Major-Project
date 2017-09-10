<?php 

function getUser($id, $field) { // Can get the fields of id, username, or password
	require 'database.php';

	$query = $conn->prepare("SELECT $field FROM users WHERE id='$id'");
	$query->execute();
	$run = $query->fetch(PDO::FETCH_ASSOC);

	return $run[$field]; // return field user requests
}

function getApiInfo($id, $field) { // Can get the fields of id, username, or password
	require 'database.php';

	$query = $conn->prepare("SELECT $field FROM api_info WHERE id='$id'");
	$query->execute();
	$run = $query->fetch(PDO::FETCH_ASSOC);

	return $run[$field]; // return field user requests
}

 ?>




