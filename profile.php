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
	<h2>User profile</h2>

	<p>Member since: X</p>
	<p>Total points: X</p>
	<p>Points today:</p>
	<p>Points past 7 days: X</p>
	
	<br/>
</body>

</html>