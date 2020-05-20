<!-- Login with a testing account for demo purposes -->

<?php
session_start();
ob_start();
?>

<script>
function redirect()
{
	window.location.replace("https://cloudfit.info/main.php");
}

	document.cookie = "username=admin";
	document.cookie = "userid=admin";
	redirect();
</script>