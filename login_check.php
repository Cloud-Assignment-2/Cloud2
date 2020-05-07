<?php
session_start();

//Receive username from client side
$entered_username = $_POST['username'];
//Receive password from client side
$entered_password = $_POST['password'];

require __DIR__ . '/vendor/autoload.php';
use Google\Cloud\Datastore\DatastoreClient;

$projectId = 'fitness-tracker-276108';
$datastore = new DatastoreClient([
'projectId' => $projectId
]);

echo "Entered:"
echo $entered_username;
echo $entered_password;

$key = $datastore->key('user', $entered_username);
$entity = $datastore->lookup($key);
if (!is_null($entity))
{
	$database_pwd = $entity['password'];
	
	echo $database_pwd;
	echo $entered_password;
	if (strcmp($database_pwd,$entered_password) === 0)
	{
		$_SESSION["loginSucc"] = False;
			// save name and key for later
		$_SESSION["login_id"] = $entered_username;
		$_SESSION["login_name"] = $entity['name'];
		// password is correct, redirect to main page
		//header('Location: main.php');
		exit();
	}
}
else
{
	echo "null";
}
$_SESSION["loginSucc"] = True;
//header('Location: index.php');

echo "login fail";

?>