<!--
	Currently only username is checked, since I think we'll replace it with third-party login service
-->

<?php
session_start();
ob_start();

//Receive username from client side
$entered_username = $_POST['username'];
//Receive password from client side
$entered_password = $_POST['password'];

//$_SESSION["login_id"] = $_POST['username'];
?>

<!-- include firebase -->
<script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-firestore.js"></script>

<script>
	// Your web app's Firebase configuration
	var firebaseConfig =
	{
		apiKey: "AIzaSyAFZBF28p1IJCd8JiC1BaV8aNCSYJq6fEo",
		authDomain: "cloudfit-277211.firebaseapp.com",
		databaseURL: "https://cloudfit-277211.firebaseio.com",
		projectId: "cloudfit-277211",
		storageBucket: "cloudfit-277211.appspot.com",
		messagingSenderId: "60375874577",
		appId: "1:60375874577:web:77f5085d5fd62c055c45b0"
	};
	// Initialize Firebase
	firebase.initializeApp(firebaseConfig);
	var db = firebase.firestore();

	var docRef = db.collection("user").doc('<?php echo $entered_username; ?>');

	docRef.get().then(function(doc)
	{
		if (doc.exists)
		{
			console.log("Password:", doc.data().password);
			<?php $_SESSION["login_id"] = $_POST['username'];?>
			window.location.replace("/main.php");
			return;
		}
		else // document not found
		{
			console.log("User not found");
		}
	}).catch(function(error) // error with database
	{
		console.log("Error getting document:", error);
	});
	window.location.replace("/index.php");
</script>