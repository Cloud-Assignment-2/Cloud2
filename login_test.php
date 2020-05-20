<!-- Login with a testing account for demo purposes -->

<?php
session_start();
ob_start();
?>

<script>
	document.cookie = "username=admin";
	document.cookie = "userid=admin";

   setTimeout(function () {
       window.location.href = "https://cloudfit.info/main.php"; //will redirect to your blog page (an ex: blog.html)
    }, 2000); //will call the function after 2 secs.

</script>