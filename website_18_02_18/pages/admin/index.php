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
<head>
	<meta charset="utf-8">
	<title>Admin Home Page</title>
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
		<h1>Admin Home Page</h1>
		<h3>Welcome to electago Admin Home Page</h3>
		<a href="elections.php"><button type="button" class="btn btn-secondary">Create/View Elections</button></a><p><p>
		<a href="results.php"><button type="button" class="btn btn-secondary">View Election Results</button></a><p><p>
		<a href="candidates.php"><button type="button" class="btn btn-secondary">Add/Edit Candidates</button></a><p><p>
		<a href="uploadKey.php"><button type="button" class="btn btn-secondary">Upload key</button></a><p></p>
		<a href="decryptScreen.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
		<a href="demographics.php"><button type="button" class="btn btn-secondary">View Demographics</button></a>

		<a href="../../includes/logout.php"><p>Log out</p></a>
	</body>


	<footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
		<ul>
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="../../index.php">Login as user</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
	</footer>
</html>
