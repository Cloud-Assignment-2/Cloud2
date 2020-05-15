<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>

<html>

<head>
    <title>CloudFit</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	
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
	
    var map, infoWindow;
	var marker; // player marker
	var watchId; // map updater
	var userMarkers = [];

	var userPos =
	{
		lat:0.0,
		lng:0.0
	};

    function initMap()
	{
		map = new google.maps.Map(document.getElementById('map'),
		{
			center: { lat: -37.806, lng: 144.954 },zoom: 14
		});
		
        infoWindow = new google.maps.InfoWindow;
		
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
            navigator.geolocation.getCurrentPosition(function(position)
			{
                // console.log(position.coords.latitude);

                var pos =
				{
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
				//marker.setPosition(pos);
                marker = new google.maps.Marker
				({
                    position: pos,
                    map: map
                });
                map.setCenter(pos);
			},
			function()
			{
				handleLocationError(true, infoWindow, map.getCenter());
			});
		}
		else
		{
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
		
		// pull existing markers from db.
        db.collection("marker").get().then(function(querySnapshot)
		{
            querySnapshot.forEach(function(doc)
			{
				console.log("entry loop");
                    var coordinates =
					{
                        lat: doc.data().location.latitude,
                        lng: doc.data().location.longitude
                    };

                    var fitMarker = new google.maps.Marker
					({
                        position: coordinates,
                        map: map,
                        icon: { url: "/fitmarker.png" }
                    });
                    userMarkers.push(fitMarker);
					
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
		
		// console.log("added: "+markers.length+" markers.");
		
		// if ( markers.length < 3 )
		// {
			// console.log("Need to add more markers.");
		// }

		// load account data from database
    }
	
    function handleLocationError(browserHasGeolocation, infoWindow, pos)
	{
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
		'Error: The Geolocation service failed.' :
		'Error: Your browser doesn\'t support geolocation.');
		infoWindow.open(map);
    }

	// use marker.setPosition(myLatlng) to update a marker
	// move map and update marker on user movement
	// function centerMap(location)
	// {
		// //alert("map update");
		// var pos =
		// {
			// lat: location.coords.latitude,
			// lng: location.coords.longitude
		// };
		
		// marker.setPosition(pos);
		// map.setCenter(pos);
		// navigator.geolocation.clearWatch(watchId);
	// }
	
	//periodically update map to update user position and check nearby markers.
	function updateMarker()
	{
		//alert("UPDATE MRKR");
		console.log("update marker");
		
		console.log("current markers: "+userMarkers.length);
		
		if ( userMarkers.length < 3 )
		{
			console.log("Need to add more markers.");
			addNewMarker();
		}
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
			// watch user position and call centermap on update.
			//watchId = navigator.geolocation.watchPosition(centerMap);
			
            navigator.geolocation.getCurrentPosition(function(position)
			{
				userPos.lat = position.coords.latitude;
				userPos.lng = position.coords.longitude;
				console.log("User pos updated to: "+userPos.lat+", "+userPos.lng);

				// Update user position
				// For now add a new marker. In future move the user marker.
				// with marker.setPosition(pos);
                marker = new google.maps.Marker
				({
                    position: userPos,
                    map: map
                });
                map.setCenter(userPos);
			},
			function()
			{
				handleLocationError(true, infoWindow, map.getCenter());
			});
		}
		else
		{
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
	}
	
	// add a random new marker for the user to navigate to
	function addNewMarker()
	{
		console.log("adding new marker");
		
		// Generate random variance from 0.001 to 0.002.
		var randomLatVariance = (Math.random() * 0.001) + 0.001;
		var randomLngVariance = (Math.random() * 0.001) + 0.001;
		
		// 50% chance of making the coords negative
		if (Math.random() >= 0.5) // flip lat
		{
			randomLatVariance = -randomLatVariance;
		}
		if (Math.random() >= 0.5) // flip long
		{
			randomLngVariance = -randomLngVariance;
		}
		
		console.log("random variance is: "+randomLatVariance+", "+randomLngVariance);
		//var randomLat = (Math.random() * 0.044484)-37.831706;
		//var randomLong = (Math.random() * 0.041199)+144.923676;
	}
	
	// Main interval function to keep track of application state
	// interval shouldn't be too often to allow time for database updates and whatnot. 30 seconds should be plenty for walking/running.
	var interval = setInterval(updateMarker, 20000);
	
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFZBF28p1IJCd8JiC1BaV8aNCSYJq6fEo&callback=initMap">
    </script>
	
	<!-- Chart code -->
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
	var data = google.visualization.arrayToDataTable([
	['Task', 'Hours per Day'],
	['Work', 11],
	['Eat', 2],
	['Commute', 2],
	['Watch TV', 2],
	['Sleep', 7]
	]);
	var options = {
	title: 'Example chart, to be used for user stats'
	};
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
	}
	</script>

</head>

<body class="is-preload">

	<center>
	<h1>CloudFit</h1>
	</center>

	<h2>Welcome, <?php echo $_SESSION["login_id"]; ?></h2>
	<p>
	<a href="/profile.php">User profile</a>
	<!--<a href="/name.php">Change name</a>-->
	<!--<a href="/password.php">Change password</a>-->
	</p>

	<p>test</p>	
	
	<br/>

	<div id="map" style="height:800px;"></div>
	
	<div id="piechart" style="width: 900px; height: 500px;"></div>

</body>

</html>