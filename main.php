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

    <script>

    var map, infoWindow;

    function initMap()
	{
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -37.806,
                lng: 144.954
            },
            zoom: 17
        });
        infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation)
		{
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

            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
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
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO-dbFSEA8jv-SxqQqhXELgftWtmIN7D4&callback=initMap">
    </script>
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
	
	<div id="map" style="height:800px;"></div>
	
	
	

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
title: 'My Daily Activities'
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