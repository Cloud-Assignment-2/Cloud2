<?php
session_start();
?>

<html>
<head>
<title>s3589028 - Assignment 1</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<h1>Change password</h1>
<p>

<?php
if ( $_SESSION["passSucc"] === False )
{
	echo "Error changing password.<br/>";
}
?>

Enter old password and new password.
<br/>
	<form action="password_check.php" method="POST">
	Old password: <input type="text" name="oldpass" id="oldpass" maxlength="20" required>
	<br/><br/>
	New password: <input type="text" name="newpass" id="newpass" maxlength="20" required>
	<br/><br/>
	<button type="submit">Change password</button>
	</form>
<br/>
<a href="/main.php">return to main.</a>
</p>
</body>
</html>