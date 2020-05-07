<?php
session_start();
?>

<html>
<head>
<title>Fitness Tracker - Login</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>

<h1>Login</h1>
<p>
<?php
if ( $_SESSION["loginSucc"]!== False )
{
	echo "User id or password is invalid.<br/>";
}
?>
<br/>
	<form action="login_check.php" method="POST">
	Username: <input type="text" name="username" id="username" maxlength="20" required>
	<br/><br/>
	<!--Password: <input type="password" name="password" id="password">-->
	Password: <input type="text" name="password" id="password" maxlength="20" required>
	<br/><br/>
	<button type="submit">Login</button>
	</form>
	
	<p>Debug account: admin, admin.</p>
	
	<a href="/register.php">register</a>
<br/>
</p>
</body>
</html>