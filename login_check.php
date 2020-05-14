<?php
session_start();
ob_start();

	if (strcmp($username,"admin") === 0 && strcmp($password,"admin") === 0)
	{
		// password is correct, redirect to cp
		header('Location: main.php');
		exit();
	}

header('Location: index.php');
exit();
?>

<script>
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

var docRef = db.collection("user").doc("admin");

docRef.get().then(function(doc) {
    if (doc.exists) {
        console.log("Document data:", doc.data());
    } else {
        // doc.data() will be undefined in this case
        console.log("No such document!");
    }
}).catch(function(error) {
    console.log("Error getting document:", error);
});		
</script>