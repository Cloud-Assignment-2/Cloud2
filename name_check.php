<?php
session_start();

//Receive username from client side
$oldname = $_POST['oldname'];
//Recieve change username from client side
$newname = $_POST['newname'];

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
	if (strcmp($entity['name'],$oldname) === 0)
	{
		$entity['name'] = $newname;
		$_SESSION["login_name"] = $newname;
		$transaction->update($entity);
		$transaction->commit();
		$_SESSION["nameSucc"] = True;
		header('Location: main.php');
		exit();
	}
}
$_SESSION["nameSucc"] = False;
header('Location: name.php');

?>