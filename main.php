<?php
session_start();
$_SESSION["nameSucc"] = True;
$_SESSION["passSucc"] = True;
?>

<!DOCTYPE HTML>
<!--
	Forty by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>

<head>
    <title>Fitness Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

    <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-firestore.js"></script>


    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
</head>

<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">


        <!-- Banner -->
        <section id="banner" class="major">
            <div class="inner">
                <header class="major">
                    <center>
                        <h1 id="title">Fitness Tracker</h1>
                    </center>
                </header>
            </div>
        </section>
		
<h1>Welcome, <?php echo $_SESSION["login_id"]; ?></h1>
<p>
<a href="/name.php">Change name</a>
<a href="/password.php">Change password</a>
</p>
        <div class="center">
            <nav>
                <ul>
                    <style>
                    .center {
                        text-align: center;
                    }


                    .navbar {
                        text-align: center;
                        display: inline;
                        margin: 0;
                        padding: 10px;
                        font-family: "Source Sans Pro", Helvetica, sans-serif;

                        font-size: 1.7em;
                    }
                    </style>

                    <li class="navbar"><a href="login">Login</a></li>
                    <li class="navbar"><a href="login">Statistics (bigquery)</a></li>
					
                </ul>
            </nav>
        </div>

    </div>
	
	
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
	src="//maps-api-ssl.google.com/maps/api/js?sensor=false&key=AIzaSyC9PBXwqzgcNzIT6AfY45hMrsmYlhB2_ro&callback=initMap"></script>
	
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
title: 'Test chart (can use this for user profile)'
};
var chart = new google.visualization.PieChart(document.getElementById('piechart'));
chart.draw(data, options);
}
</script>
</head>
<body>
<div id="piechart" style="width: 900px; height: 500px;"></div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>