<?php 
session_start();
include 'database.php';
include 'functions.php';

$action = $_GET['action'];
$user = $_GET['user'];
$current_id = $_SESSION['user_id'];

// Send friend request
if ($action == 'send') {
	$conn->query("INSERT INTO friend_request VALUES('', '$current_id', '$user')");
}

// Cancel friend request
if ($action == 'cancel') {
	$conn->query("DELETE FROM friend_request WHERE sender='$current_id' AND receiver='$user'");
}

// Accept friend request
if ($action == 'accept') {
	$conn->query("DELETE FROM friend_request WHERE sender='$user' AND receiver='$current_id'");
	$conn->query("INSERT INTO friends VALUES('', '$user', '$current_id')");
}

// Unfriend a friend
if ($action == 'unfriend') {
	$conn->query("DELETE FROM friends WHERE (user_a='$current_id' AND user_b='$user') OR (user_a='$user' AND user_b='$current_id')");
}

// Ignore a friend request
if ($action == 'ignore') {
	$conn->query("DELETE FROM friend_request WHERE sender='$user' AND receiver='$current_id'");
}
header('location: profile.php?user='.$user);
?>