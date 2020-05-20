<html lang="en">
  <head>
	<meta name="google-site-verification" content="zzpNvvwT05TdkinZokxOQldINIS7UrKSxrQywLqIcSU" />
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="60375874577-dikibnuoff7e97jpfe9bk81khld3gc7u.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
 
<style>
body
{
	background-image: url('bkg.jpg');
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
		var idCookie = "userid="+profile.id_token;
		
		document.cookie = usernameCookie;
		document.cookie = idCookie;
		
		//make sure this user exists in db, otherwise make a record.
		

		document.getElementById("gLogin").innerHTML = 'Google Authorisation successful. <a href="https://cloudfit.info/main.php">Continue</a>';
      }
    </script>
	
	<h1>CloudFit - Fitness Tracking App</h1>
	
	<p id="gLogin"></p>
	
	<p>Sign in with your Google account or use the <a href="cloudfit.info/login_test.php">Testing Account</a>.</p>
  </body>
</html>	