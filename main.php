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
	
	<script>
    var map, infoWindow;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -37.806,
                lng: 144.954
            },
            zoom: 17
        });
        infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                console.log(position.coords.latitude);

                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };


                var marker = new google.maps.Marker({
                    position: pos,
                    map: map
                });
                map.setCenter(pos);


                var markers = []
                console.log(typeof(markers));

            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfE56Z-lS3W0EsyQqa-M87ZR2TBZ4GKFI&callback=initMap">
    </script>
</head>
	
</head>

<body class="is-preload">

<h1 id="title">Fitness Tracker</h1>
<p>test</p>	

<br/>

	<div id="map" style="height:800px;"></div>

</body>

</html>