<?php 

$dsn = 'mysql:dbname=cs39440_16_17_cjc13;host=db.dcs.aber.ac.uk';
$username = 'cjc13';
$password = 'cjc13db';

try{
	$conn = new PDO($dsn, $username, $password);
} catch(PDOException $e) {
	die("Connection denied: " . $e->getMessage());
}

?>