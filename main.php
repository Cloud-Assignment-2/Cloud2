<?php
session_start();
$_SESSION["nameSucc"] = True;
$_SESSION["passSucc"] = True;
?>

<!DOCTYPE HTML>

<html>

<head>
    <title>Fitness Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
</head>

<body>
<h1 id="title">Fitness Tracker</h1>
<p>test</p>	

<br/>

	<!-- Draw map and populate with markers if user has any-->
    <div id="map"></div>
    <script>
		// Note: This example requires that you consent to location sharing when
		// prompted by your browser. If you see the error "The Geolocation service
		// failed.", it means you probably did not give permission for the browser to
		// locate you.
		var map, infoWindow;
		function initMap()
		{
			//alert("init map");
			
			map = new google.maps.Map(document.getElementById('map'),
			{
				center: {lat: -37.8, lng: 145.1},
				zoom: 12
			});
			infoWindow = new google.maps.InfoWindow;

			// Try HTML5 geolocation.
			if (navigator.geolocation)
			{
				navigator.geolocation.getCurrentPosition
				(
					// {
						// enableHighAccuracy: true,timeout : 5000
					// },
					function(position)
					{
						//alert("Lat: " + position.coords.latitude + "\nLon: " + position.coords.longitude);
						var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

						infoWindow.setPosition(pos);
						infoWindow.setContent('Location found.');
						infoWindow.open(map);
						map.setCenter(pos);
					},
					function()
					{
						handleLocationError(true, infoWindow, map.getCenter());
					}
					// function (error)
					// {
						// alert("error");
					// }
	  
				);
			}
			else
			{
				// Browser doesn't support Geolocation
				handleLocationError(false, infoWindow, map.getCenter());
			}
		}

		function handleLocationError(browserHasGeolocation, infoWindow, pos)
		{
			infoWindow.setPosition(pos);
			infoWindow.setContent(browserHasGeolocation ?
			'Error: The Geolocation service failed.' :
			'Error: Your browser doesn\'t support geolocation.');
			infoWindow.open(map);
		}
    </script>
	
	<script async defer type="text/javascript"
	src="//maps-api-ssl.google.com/maps/api/js?sensor=false&key=AIzaSyBfE56Z-lS3W0EsyQqa-M87ZR2TBZ4GKFI&callback=initMap"></script>

</body>

</html>