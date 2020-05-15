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
	
	// var pos =
	// {
		// lat: 0,
		// lng: 0
	// }
	
    var map, infoWindow;
	var marker; // player marker
	var watchId; // map updater

    function initMap()
	{
		map = new google.maps.Map(document.getElementById('map'),
		{
			center: { lat: -37.806, lng: 144.954 },zoom: 12
		});
		
        infoWindow = new google.maps.InfoWindow;
	

        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
			// watch user position and call centermap on update.
			//watchId = navigator.geolocation.watchPosition(centerMap);
			
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

		var markers = []
		console.log(typeof(markers));
		
        db.collection("marker").get().then(function(querySnapshot) {
            querySnapshot.forEach(function(doc)
			{
                    var coordinates = {
                        lat: doc.data().location.latitude,
                        lng: doc.data().location.longitude
                    };
					
                    var fitMarker = new google.maps.Marker
					({
                        position: coordinates,
                        map: map,
                        icon: {
                            url: "http://maps.google.com/mapfiles/kml/pal2/icon13.png"
                        }
                    });
                    markers.push(fitMarker);
            });
        }).catch(function(error) {
            console.log("Error getting documents: ", error);
        });

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
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
			// watch user position and call centermap on update.
			//watchId = navigator.geolocation.watchPosition(centerMap);
			
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
	}
	
	var interval = setInterval(updateMarker, 30000);
	
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