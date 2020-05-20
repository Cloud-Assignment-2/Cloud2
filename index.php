<html lang="en">
  <head>
	<meta name="google-site-verification" content="zzpNvvwT05TdkinZokxOQldINIS7UrKSxrQywLqIcSU" />
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="60375874577-dikibnuoff7e97jpfe9bk81khld3gc7u.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
	
	<!-- include firebase -->
	<script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-firestore.js"></script>
  </head>
  <body>

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

	// var docRef = db.collection("user").doc('<?php echo $entered_username; ?>');

	// docRef.get().then(function(doc)
	// {
		// if (doc.exists)
		// {
			// console.log("Password:", doc.data().password);
			// <?php $_SESSION["login_id"] = $_POST['username'];?>
			// window.location.replace("https://cloudfit.info/main.php");
			// return;
		// }
		// else // document not found
		// {
			// console.log("User not found");
			// window.location.replace("https://cloudfit.info");
		// }
	// }).catch(function(error) // error with database
	// {
		// console.log("Error getting document:", error);
		// window.location.replace("https://cloudfit.info");
	// });
	
</script>

<style>
body
{
	background-image: url('bkgCA.jpg');
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-size: cover;
}
</style>
  
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        console.log("ID: " + profile.getId());
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());

        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);
		
		var usernameCookie = "username="+profile.getGivenName();
		var idCookie = "userid="+id_token;
		
		document.cookie = usernameCookie;
		document.cookie = idCookie;
		
		//make sure this user exists in db, otherwise make a record.
		

		document.getElementById("gLogin").innerHTML = 'Google Authorisation successful. <a href="https://cloudfit.info/main.php">Continue</a>';
      }
	  
	  function setDemo()
	  {
		  console.log("SET DEMO");
	  }
    </script>
	
	<h1>CloudFit - Fitness Tracking App</h1>
	
	<p id="gLogin"></p>
	<p>Sign in with your Google account or use the <a href="https://cloudfit.info/main.php" onclick="setDemo()">Demo Account</a>.</p>
  </body>
</html>	