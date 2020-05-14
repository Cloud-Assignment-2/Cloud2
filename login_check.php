<?php
session_start();
ob_start();

	if (strcmp($username,"admin") === 0 && strcmp($password,"admin") === 0)
	{
		// password is correct, redirect to cp
		header('Location: main.php');
		exit();
	}

header('Location: index.php');
exit();
?>
