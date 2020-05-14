<?php
session_start();

	$_SESSION["login_id"] = 'admin';
	$_SESSION["login_name"] = 'admin';
	// redirect to main page
	header('Location: main.php');
	exit();
?>