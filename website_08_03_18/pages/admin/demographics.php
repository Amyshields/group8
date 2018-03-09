<?php
require_once('../../includes/functions.php'); 

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");  
}
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Demographics</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="../../css/electago.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
			
	<body>
	<header class="container-fluid text-center">
		<div id="logo">
			<img src="../../images/logo.png" width="300" height="100" alt="">
		</div>
	</header>
		<h1>Voting Demographics</h1>
		
		<h3>Please select which Election you would like to view the demographics for:</h3>
		
		<h2>Result</h2>
		<img src="../../images/pie.png" width="570" height="320" alt="">
		
		<h2>Turnout</h2>
		<img src="../../images/pie.png" width="570" height="320" alt="">
		
		<p>All the following data is given by volunteers</p>
		
		<h2>Vote by age</h2>
		<img src="../../images/stacked.png" width="570" height="320" alt="">
		<p>https://canvasjs.com/javascript-charts/stacked-bar-chart/</p>
		
		<h2>Vote by gender</h2>
		<img src="../../images/column.png" width="570" height="320" alt="">
		
		<h2>Vote by employment status</h2>
		<img src="../../images/stacked.png" width="570" height="320" alt="">
		<p>Split by number of hours worked (https://yougov.co.uk/news/2017/06/13/how-britain-voted-2017-general-election/)</p>
		
		<h2>Vote by Ethnicity</h2>
		<img src="../../images/multi.png" width="570" height="320" alt="">
		
		
		<h2>Vote compared to previous years</h2>
		<img src="../../images/column.png" width="570" height="320" alt="">
		<p>We can make data negative to create a swing graph</p>
	</body>

	<footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
		<ul>
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="index.php">Back</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
	</footer>

		<div id="small_logo">
		<img src="../../images/small_logo.png" width="100" height="35" alt="">
	</div>
</html>