<?php
session_start();
ob_start();

//Receive username from client side
$entered_username = $_POST['username'];
//Receive password from client side
$entered_password = $_POST['password'];
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

// db.collection("user").get().then(function(querySnapshot) {
            // querySnapshot.forEach(function(doc) {
                // if (doc.data().available) {
                    // var coordinates = {
                        // lat: doc.data().location.latitude,
                        // lng: doc.data().location.longitude
                    // };
					
					
// Create a reference to the cities collection
//var citiesRef = db.collection("user");

// Create a query against the collection.
//var query = citiesRef.where("user", "==", '<?php echo $username; ?>');

var docRef = db.collection("user").doc('<?php echo $entered_username; ?>');

docRef.get().then(function(doc) {
    if (doc.exists) {
        console.log("Password:", doc.data().password);
    } else {
        // doc.data() will be undefined in this case
        console.log("User not found");
		<?php header('Location: index.php');?>
    }
}).catch(function(error) {
    console.log("Error getting document:", error);
});		
</script>