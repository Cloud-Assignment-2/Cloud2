<?php
session_start();

$oldpass = $_POST['oldpass'];
$newpass = $_POST['newpass'];

require __DIR__ . '/vendor/autoload.php';
use Google\Cloud\Datastore\DatastoreClient;

$projectId = 'cloud-assignment1-part2-272304';
$datastore = new DatastoreClient([
'projectId' => $projectId
]);

$transaction = $datastore->transaction();
$key = $datastore->key('user', $_SESSION["login_id"]);
$entity = $transaction->lookup($key);

if (!is_null($entity))
{
	if (strcmp($entity['password'],$oldpass) === 0)
	{
		$entity['password'] = $newpass;
		$_SESSION["login_name"] = $newpass;
		$transaction->update($entity);
		$transaction->commit();
		$_SESSION["passSucc"] = True;
		header('Location: login.php');
		exit();
	}
}

$_SESSION["passSucc"] = False;
header('Location: password.php');

?>