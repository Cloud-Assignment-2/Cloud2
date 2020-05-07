<?php
session_start();
?>

<html>
<head>
<title>s3589028 - Assignment 1</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
<h1>Change name</h1>
<p>

<?php
if ( $_SESSION["nameSucc"] === False )
{
	echo "Error changing name.<br/>";
}
?>

Enter old name and new name.
<br/>
	<form action="name_check.php" method="POST">
	Old name: <input type="text" name="oldname" id="oldname" maxlength="20" required>
	<br/><br/>
	New name: <input type="text" name="newname" id="newname" maxlength="20" required>
	<br/><br/>
	<button type="submit">Change name</button>
	</form>
<br/>
<a href="/main.php">return to main.</a>
</p>
</body>
</html>