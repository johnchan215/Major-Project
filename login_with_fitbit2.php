<?php
// Starting a session
session_start();

require 'database.php';
require 'functions.php';

// If the user_id is set then there is a current user
if( isset($_SESSION['user_id']) ){

	// If the ID is the same as the user's ID
	$records = $conn->prepare('SELECT id,username,password FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$user = NULL;

	// If user is found, then set the user as the results
	if( count($results) > 0){
		$user = $results;
	}

}
///////////////////////////////////////////////////////////////////////////////////////////
/*
 * login_with_fitbit2.php
 *
 * @(#) $Id: login_with_fitbit2.php,v 1.1 2015/07/23 20:45:00 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Fitbit2';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_fitbit2.php';

	$client->client_id = '228595'; $application_line = __LINE__;
	$client->client_secret = '20bc33d23c4fbd62f077b39c72ef97dd';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Fitbit application registration page https://dev.fitbit.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri).' Make sure this URL is '.
			'not in a private network and accessible to the Fitbit site.';

	/* API permissions
	 */

	// Changed for application to look at activity and profile scope
	$client->scope = 'activity profile'; 
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.fitbit.com/1/user/-/profile.json',
					'GET', array(), array('FailOnAccessError'=>true), $user);

				// Added to look at activities for today
                $activitySuccess = $client->CallAPI(
                    'https://api.fitbit.com/1/user/-/activities/date/today.json', 
                    'GET', array(), array('FailOnAccessError'=>true), $activities);
			}
		}
		$success = $client->Finalize($success);
		$activitySuccess = $client->Finalize($activitySuccess);
	}

	if($client->exit)
		exit;
	if($success && $activitySuccess)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Fitbit OAuth client results</title>
</head>
<body>
<?php
	/*
	 * Count a row the logged in user ID is found in the api_info table
	 */
	$current_id = $_SESSION['user_id'];
	$user_query = $conn->prepare("SELECT COUNT(*) FROM api_info WHERE user='$current_id'");
	$user_query->execute();
	$num_rows = $user_query->fetchColumn();

	$user_steps = $activities->summary->steps; // Fetch steps from JSON
	$user_floors = $activities->summary->floors; // Fetch floors from JSON
	$user_distance = $activities->summary->distances[0]->distance; // Fetch distance from JSON
	$user_calories = $activities->summary->caloriesOut; // Fetch calories from JSON

	/* 
	 * Store API data to database
	 */

	// Replace existing values if logged in user ID is found
	if ($num_rows == 1) { 
		$conn->query("DELETE FROM api_info WHERE user='$current_id'");
		$conn->query("INSERT INTO api_info VALUES('', '$user_steps', '$user_floors', '$user_distance', '$user_calories', '$current_id')");
	
	// Insert new values if logged in user ID is not found
	} else { 
		$conn->query("INSERT INTO api_info VALUES('', '$user_steps', '$user_floors', '$user_distance', '$user_calories', '$current_id')");
	}

	// Redirect user back to index
	header("Location: index.php");
?>
</body>
</html>
<?php
	} else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OAuth client error</title>
</head>
<body>
<h1>OAuth client error</h1>
<p>Error: <?php echo HtmlSpecialChars($client->error); }?></p>
</body>
</html>
