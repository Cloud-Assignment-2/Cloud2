<!-- Login with a testing account for demo purposes -->

<?php
session_start();
ob_start();
?>

<script>
	document.cookie = "username=admin";
	document.cookie = "userid=admin";
	window.location.replace("https://cloudfit.info/main.php");
</script>