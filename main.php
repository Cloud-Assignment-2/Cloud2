<?php
session_start();
$_SESSION["nameSucc"] = True;
$_SESSION["passSucc"] = True;
?>

<html>
<head>
<title>s3589028 - Assignment 1</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>

<h1>Welcome, <?php echo $_SESSION["login_name"]; ?></h1>
<p>
<a href="/name.php">Change name</a>
<a href="/password.php">Change password</a>
</p>
</body>
</html>