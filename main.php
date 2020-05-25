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
<!-- OpenWeatherMap API -->
<!-- <script type="text/javascript" src="weather.js"></script> -->

<script>
	function getCookie(cname)
	{
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++)
		{
			var c = ca[i];
			while (c.charAt(0) == ' ')
			{
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0)
			{
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

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
	
    var map, infoWindow, distanceService;
	var marker; // player marker
	var watchId; // map updater
	var userMarkers = [];
	var markerID = [];
	
	var removeDistantID = "none"; // distant marker flagged to remove
	var removeCloseID = "none"; // close marker flagged to remove
	
	var closestMarkerID=-1;
	var closestDistance=1000;
	
	// count of user's point total.
	var totalPoints = 0;
	
	var MAX_MARKERS = 6; // 6 should be enough to provide good options for a destination.
	var CREDIT_DISTANCE=55; // distance user must close to a marker to be credited. Shouldn't be too precise because sometimes GPS is inaccurate or slow to update.
	var UPDATE_INTERVAL = 5000; // 5 seconds should provide enough time between database updates

	var userPos =
	{
		lat:0.0,
		lng:0.0
	};
	var closestMarkerPos =
	{
		lat:0.0,
		lng:0.0
	};

    function initMap()
	{
		// initialize distance matrix service
		distanceService = new google.maps.DistanceMatrixService();
		
		map = new google.maps.Map(document.getElementById('map'),
		{
			center: { lat: -37.806, lng: 144.954 },zoom: 14
		});
		
        infoWindow = new google.maps.InfoWindow;
		
		// initialise user location marker
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
            navigator.geolocation.getCurrentPosition(function(position)
			{
                // console.log(position.coords.latitude);
				userPos.lat = position.coords.latitude;
				userPos.lng = position.coords.longitude;
				//console.log("User pos updated to: "+userPos.lat+", "+userPos.lng);

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
		
		// pull existing markers from db.
		db.collection("marker").where("user", "==", getCookie("userid")).get().then(function(querySnapshot)
		{
            querySnapshot.forEach(function(doc)
			{
				//console.log("entry loop");
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
	
    function handleLocationError(browserHasGeolocation, infoWindow, pos)
	{
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
		'Error: The Geolocation service failed.' :
		'Error: Your browser doesn\'t support geolocation.');
		infoWindow.open(map);
    }
	
    function updateWeather()
	{
		var query = "https://api.openweathermap.org/data/2.5/weather?lat="+userPos.lat+"&lon="+userPos.lng+"&appid=a17a7543c275c8b5d7f4452e1104a330";
		
        //$.getJSON("https://api.openweathermap.org/data/2.5/weather?q=London&APPID=a17a7543c275c8b5d7f4452e1104a330",function(json)
        //$.getJSON("https://api.openweathermap.org/data/2.5/weather?lat=-37.878873&lon=145.089415&appid=a17a7543c275c8b5d7f4452e1104a330",function(json)
        $.getJSON(query,function(json)
		{
            //document.write(JSON.stringify(json));
			var temp = json["main"]["temp"]-273.15;
			document.getElementById("htmlTemp").innerHTML = 'Current temperature: '+Math.round(temp)+'ºC';
        });
    }
	
	function updateElevation()
	{
		// Elevation service
		var elevator = new google.maps.ElevationService;
		
		// Initiate the elevation request
		elevator.getElevationForLocations
		({
			'locations': [userPos]
		},
		function(results, status)
		{
			if (status === 'OK')
			{
				// Retrieve the first result
				if (results[0])
				{
					// Update user elevation output
					document.getElementById("htmlElevation").innerHTML = 'Current elevation: '+Math.round(results[0].elevation)+'m';
				}
				else
				{
					console.log("No elevation returned for given location.");
				}
			}
			else
			{
				console.log('Elevation service failed due to: ' + status);
			}
		});
	}
	
	function updateDistanceMatrix()
	{
		if (closestMarkerPos.lat == 0.0)
		{ return; }
		
		console.log("update distance matrix");

		distanceService.getDistanceMatrix(
		{
			origins: [userPos],
			destinations: [closestMarkerPos],
			travelMode: 'WALKING',
		}, callback);
		
		function callback(response, status)
		{
			if (status == 'OK')
			{
				var origins = response.originAddresses;
				var destinations = response.destinationAddresses;

				for (var i = 0; i < origins.length; i++)
				{
					var results = response.rows[i].elements;
					for (var j = 0; j < results.length; j++)
					{
						var element = results[j];
						var distance = element.distance.value;
						var duration = element.duration.text;
						var from = origins[i];
						var to = destinations[j];
						
						//update distance
						console.log("distance matrix returns: "+distance);
						document.getElementById("htmlClosest").innerHTML = 'Distance to closest marker: '+distance + 'm';
						
					}
				}
			}
		}
	}

	//periodically update map to update user position and check nearby markers.
	function updateLoop()
	{
		// step 1: update user location
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
		{
			// watch user position and call centermap on update.
            navigator.geolocation.getCurrentPosition(function(position)
			{
				userPos.lat = position.coords.latitude;
				userPos.lng = position.coords.longitude;
				//console.log("User pos updated to: "+userPos.lat+", "+userPos.lng);
				document.getElementById("htmlPos").innerHTML = 'Current position: '+userPos.lat+', '+userPos.lng;

				// Update user position marker
				marker.setPosition(userPos);
                map.setCenter(userPos);
				
				// we can also calculate distance moved here.
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
		
		//update player score
		document.getElementById("htmlScore").innerHTML = 'Total score: '+totalPoints;
		getUserCredits();
		
		// Remove distant markers
		// or credit close markers. Avoid doing both at once
		// to avoid any sync issues.
		if (removeDistantMarkers())
		{
		}
		else
		{
			creditCloseMarkers();
		}

		// always maintain MAX_MARKERS markers
		if ( userMarkers.length < MAX_MARKERS )
		{
			//console.log("Need to add more markers.");
			addNewMarker();
		}
		// Find elevation for user's current position.
		updateElevation();
		// Get temperature at user's current position
		updateWeather();
		// update walking distance to nearest marker
		updateDistanceMatrix();
	}
	
	// remove all markers and then pull them from db again.
	function updateMarkers()
	{
		var arrayLength = userMarkers.length;
		for (var i = 0; i < arrayLength; i++)
		{
			userMarkers[i].setMap(null);
		}
		
		userMarkers = [];
		markerID = [];
		
		// pull existing markers from db.
		db.collection("marker").where("user", "==", getCookie("userid")).get().then(function(querySnapshot)
		{
            querySnapshot.forEach(function(doc)
			{
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
		//console.log("Function: Remove distant markers.");
		// pull existing markers from db.
		db.collection("marker").where("user", "==", getCookie("userid")).get().then(function(querySnapshot)
		{
            querySnapshot.forEach(function(doc)
			{
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
	
	function getUserCredits()
	{
		totalPoints=0;
		db.collection("points").where("username", "==", getCookie("userid")).get().then(function(querySnapshot)
		{
            querySnapshot.forEach(function(doc)
			{
				totalPoints = totalPoints+1;
				//console.log("point counted");
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
		//console.log("returning points: "+totalPoints);
	}
	
	function creditCloseMarkers()
	{
		closestMarkerID=-1;
		closestDistance=1000;

		// pull existing markers from db.
		db.collection("marker").where("user", "==", getCookie("userid")).get().then(function(querySnapshot)
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
				
				if (distanceFromUser < closestDistance)
				{
					closestMarkerID = doc.id;
					closestDistance = distanceFromUser;
					console.log("update close dist to "+closestDistance);
					
					closestMarkerPos.lat = coordinates.lat;
					closestMarkerPos.lng = coordinates.lng;
				}
				
				if (distanceFromUser < CREDIT_DISTANCE)
				{
					removeCloseID = doc.id;
					console.log("Removing close marker: "+removeCloseID);
				}
					
            });
        }).catch(function(error)
		{
            console.log("Error getting documents: ", error);
        });
		
		if (removeCloseID.localeCompare("none")!=0)
		{
			db.collection("marker").doc(removeCloseID).delete().then(function()
			{
				removeCloseID="none";
				updateMarkers();
				
				//credit the player with a point
				var dbTimestamp = firebase.firestore.Timestamp.fromDate(new Date());
				
				// Add a new document in collection "cities"
				db.collection("points").add
				({
					username: getCookie("username"),
					timestamp: dbTimestamp
				})

				return true;
				
			}).catch(function(error)
			{
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
			var coordinates =
			{
				lat: userPos.lat+randomLatVariance,
				lng: userPos.lng+randomLngVariance
			};
			
			// snap the coordinates to the nearest road
			var coordString = coordinates.lat.toString() + "," + coordinates.lng.toString();
			console.log("Snapping: "+coordString);

			$.get('https://roads.googleapis.com/v1/snapToRoads',
			{
				interpolate: false,
				key: "AIzaSyAFZBF28p1IJCd8JiC1BaV8aNCSYJq6fEo",
				path: coordString
			},
			function(data)
			{
				placeMarkerAt(data.snappedPoints[0].location.latitude,data.snappedPoints[0].location.longitude);
				console.log("Snap done");
			});
			console.log("End of snap func");

		}
		else
		{
			console.log("user coords not valid");
		}
	}
	
	function placeMarkerAt(lat, lng)
	{
		var snappedCoordinates =
		{
			lat: lat,
			lng: lng
		};

		var fitMarker = new google.maps.Marker
		({
			position: snappedCoordinates,
			map: map,
			icon: { url: "/fitmarker.png" }
		});
		userMarkers.push(fitMarker);
		
		console.log("additional marker added");
		
		// add to db.
		db.collection("marker").add
		({
			location: new firebase.firestore.GeoPoint(snappedCoordinates.lat, snappedCoordinates.lng),
			user: getCookie("userid")
		});
		
		console.log("marker added to db");
	}
	
	// Main interval function to keep track of application state
	// interval shouldn't be too often to allow time for database updates and whatnot. 30 seconds should be plenty for walking/running.
	var interval = setInterval(updateLoop, UPDATE_INTERVAL);
	
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
</head>

<body class="is-preload">

	<center>
	<h1>CloudFit</h1>
	</center>

	<h2 id="welcomeuser"></h2>

	<p id="htmlScore">Total score:</p>
	<p id="htmlClosest">Distance to closest marker:</p>
	<p id="htmlPos">User coordinates:</p>
	<p id="htmlElevation">User elevation:</p>
	<p id="htmlTemp">Current temperature:</p>
	
	<!-- Distance matrix: https://developers.google.com/maps/documentation/distance-matrix/intro -->
	
	<br/>

	<div id="map" style="height:800px;"></div>

</body>

<script>
	document.getElementById("welcomeuser").innerHTML = "Welcome, "+getCookie("username");
</script>

</html>