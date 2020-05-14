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
db.collection("user").get().then(function(querySnapshot) {
            querySnapshot.forEach(function(doc) {
                if (doc.data().available) {
                    var coordinates = {
                        lat: doc.data().location.latitude,
                        lng: doc.data().location.longitude
                    };
					
					
// Create a reference to the cities collection
//var citiesRef = db.collection("user");

// Create a query against the collection.
//var query = citiesRef.where("user", "==", '<?php echo $username; ?>');

db.collection("user").where("user", "==", '"admin")
    .get()
    .then(function(querySnapshot) {
        querySnapshot.forEach(function(doc) {
            // doc.data() is never undefined for query doc snapshots
            console.log("SUCC");
        });
    })
    .catch(function(error) {
        console.log("Error getting documents: ", error);
    });
					
</script>