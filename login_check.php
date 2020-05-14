<?php
session_start();

//Receive username from client side
$entered_username = $_POST['username'];
//Receive password from client side
$entered_password = $_POST['password'];

if ( $entered_password == "" && $entered_username == "" )
{
	header('Location: main.php');
	exit();
}

require __DIR__ . '/vendor/autoload.php';
use Google\Cloud\Datastore\DatastoreClient;

$projectId = 'cloudfit';
$datastore = new DatastoreClient([
'projectId' => $projectId
]);

$key = $datastore->key('User', $entered_username);
$entity = $datastore->lookup($key);
if (!is_null($entity))
{
	$database_pwd = $entity['password'];

	if (strcmp($database_pwd,$entered_password) === 0)
	{
		$_SESSION["loginSucc"] = False;
			// save name and key for later
		$_SESSION["login_id"] = $entered_username;
		$_SESSION["login_name"] = $entity['name'];
		// password is correct, redirect to main page
		header('Location: main.php');
		//echo "login success";
		exit();
	}
}
else
{
	//echo "null";
}
$_SESSION["loginSucc"] = True;
header('Location: index.php');

//echo "login fail";

?>

<p>Hello</p>