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
<!-- Jquery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>

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
	var markerID = [];
	
	var removeDistantID = "none"; // distant marker flagged to remove
	var removeCloseID = "none"; // close marker flagged to remove

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
		
		// get user location
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
					markerID.push(doc.id);
					
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
	function updateLoop()
	{
		// step 1: update user location
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
		
		if (userPos.lat==0.0 || userPos.lng==0.0)
		{
			console.log("Not initialized yet, returning update");
			return;
		}
		// step 1: Remove distant markers
		// or credit close markers. Avoid doing both at once
		// to avoid any sync issues.
		if (removeDistantMarkers())
		{
		}
		else
		{
			creditCloseMarkers();
		}
		
		//alert("UPDATE MRKR");
		console.log("update marker");
		
		console.log("current markers: "+userMarkers.length);
		
		<!-- always maintain 3 markers -->
		if ( userMarkers.length < 3 )
		{
			console.log("Need to add more markers.");
			addNewMarker();
		}
	}
	
	// remove all markers and then pull them from db again.
	function updateMarkers()
	{
		var arrayLength = userMarkers.length;
		for (var i = 0; i < arrayLength; i++)
		{
			userMarkers[i].setMap(null);
			//console.log(myStringArray[i]);
			//Do something
		}
		
		userMarkers = [];
		markerID = [];
		
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
					markerID.push(doc.id);
					
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
	}
	
	//check if any markers are too far away and should be deleted
	function removeDistantMarkers()
	{
		console.log("Function: Remove distant markers.");
		//getDistance
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

                    var distanceFromUser = getDistance(userPos.lat, userPos.lng, coordinates.lat, coordinates.lng);
					console.log("Marker dist: "+distanceFromUser);
					
					if (distanceFromUser > 500)
					{
						removeDistantID = doc.id;
						console.log("Removing distant marker: "+removeDistantID);
					}
					
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
		
		console.log("Final id: "+removeDistantID);
		if (removeDistantID.localeCompare("none")!=0)
		{
			console.log("Remove id: "+removeDistantID);

			db.collection("marker").doc(removeDistantID).delete().then(function()
			{
				console.log("Document successfully deleted!");
				console.log("Remove distant marker.");
				removeDistantID="none";
				updateMarkers();
				return true;
				
			}).catch(function(error) {
				console.error("Error removing document: ", error);
			});

		}
		return false;
	}
	
	function creditCloseMarkers()
	{
		console.log("Function: Credit close markers.");
		//getDistance
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

                    var distanceFromUser = getDistance(userPos.lat, userPos.lng, coordinates.lat, coordinates.lng);
					console.log("Marker dist: "+distanceFromUser);
					
					if (distanceFromUser < 50)
					{
						removeCloseID = doc.id;
						console.log("Removing close marker: "+removeCloseID);
					}
					
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
		
		console.log("Final id: "+removeCloseID);
		if (removeCloseID.localeCompare("none")!=0)
		{
			console.log("Remove id: "+removeCloseID);

			db.collection("marker").doc(removeCloseID).delete().then(function()
			{
				console.log("Document successfully deleted!");
				console.log("Remove close marker.");
				removeCloseID="none";
				updateMarkers();
				
				//credit the player with a point
				var dbTimestamp = firebase.firestore.Timestamp.fromDate(new Date());
				
				// Add a new document in collection "cities"
				db.collection("points").add
				({
					username: "admin",
					timestamp: dbTimestamp
				})

				return true;
				
			}).catch(function(error) {
				console.error("Error removing document: ", error);
			});

		}
		return false;
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
		console.log("user pos: "+userPos.lat);
		
		// make sure user has valid coordinates
		if ( userPos.lat != 0 && userPos.lng != 0)
		{
			//console.log("set relative to user here");
			
			var coordinates =
			{
				lat: userPos.lat+randomLatVariance,
				lng: userPos.lng+randomLngVariance
			};
			
			// snap the coordinates to the nearest road
			//https://roads.googleapis.com/v1/snapToRoads?path=-35.27801,149.12958&key=AIzaSyAFZBF28p1IJCd8JiC1BaV8aNCSYJq6fEo
			console.log("SNAP TO ROAD");
			
			snapToRoad(coordinates.lat, coordinates.lng);


			var fitMarker = new google.maps.Marker
			({
				position: coordinates,
				map: map,
				icon: { url: "/fitmarker.png" }
			});
			userMarkers.push(fitMarker);
			
			console.log("additional marker added");
			
			// add to db.
			db.collection("marker").add
			({
				location: new firebase.firestore.GeoPoint(coordinates.lat, coordinates.lng),
				user: '<?php echo $_SESSION["login_id"]; ?>'
			});
			
			console.log("marker added to db");
		}
		else
		{
			console.log("user coords not valid");
		}
	}
	
	// Main interval function to keep track of application state
	// interval shouldn't be too often to allow time for database updates and whatnot. 30 seconds should be plenty for walking/running.
	var interval = setInterval(updateLoop, 10000);

	// Snap a user-created polyline to roads and draw the snapped path
	function snapToRoad(lat, lng)
	{
		var coordString = lat.toString() + "," + lng.toString();
		console.log("Snapping: "+coordString);

		$.get('https://roads.googleapis.com/v1/snapToRoads',
		{
			interpolate: false,
			key: "AIzaSyAFZBF28p1IJCd8JiC1BaV8aNCSYJq6fEo",
			path: coordString
		},
		function(data)
		{
			console.log("Snap done");
		//processSnapToRoadResponse(data);
		//drawSnappedPolyline();
		});
		console.log("End of snap func");
	}
	
	// Get distance between two geopoints
	function getDistance (lat1, lng1, lat2, lng2 ) 
	{
		var earthRadius = 3958.75;
		var dLat = toRadians(lat2-lat1);
		var dLng = toRadians(lng2-lng1);
		var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
		Math.sin(dLng/2) * Math.sin(dLng/2);
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		var dist = earthRadius * c;

		var meterConversion = 1609.0;

		return dist * meterConversion;
	}
	function toRadians(degrees)
	{
	  var pi = Math.PI;
	  return degrees * (pi/180);
	}
		
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