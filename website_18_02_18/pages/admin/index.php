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
		<header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='images/logo.png' width='300' height='100' alt=''>
                        </div>
        </header>
        <div class="container">
		<div class="container-fluid col-sm-offset-2">
			<h3>Welcome to electago Admin Home Page</h3>

			<div class="card" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Create/View Elections</h5>
			    <p class="card-text">Click here to create a new election or to view all current elections.</p>
			    <a href="elections.php" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-menu-right"></span>
			    	<span class="glyphicon glyphicon-menu-right"></span></a>
			  </div>
			</div>

			<div class="card" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">View Election Results</h5>
			    <p class="card-text">Click the arrows below.</p>
			    <a href="elections.php" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-menu-right"></span>
			    	<span class="glyphicon glyphicon-menu-right"></span></a>
			  </div>
			</div>

			<div>
				<a href="elections.php"><button type="button" class="btn btn-secondary">Create/View Elections</button></a><p></p>
				<a href="results.php"><button type="button" class="btn btn-secondary">View Election Results</button></a><p></p>
				<a href="candidates.php"><button type="button" class="btn btn-secondary">Add/Edit Candidates</button></a><p></p>
				<a href="uploadKey.php"><button type="button" class="btn btn-secondary">Upload key</button></a><p></p>
				<a href="decryptScreen.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
				<a href="demographics.php"><button type="button" class="btn btn-secondary">View Demographics</button></a>
			</div>


					<div class="card" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Card title</h5>
			    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
			    <a href="#" class="btn btn-primary">Go somewhere</a>
			  </div>
			</div>

			<a href="../../includes/logout.php"><button type="button" class="btn btn-alert">Log out</button></a>
			</div>
		</div>
		<footer class="container-fluid text-left">
			<!--info here: logo, copyright, links, login as admin-->
			<ul>
				<li><a href="#">Help</a></li>
				<li><p>Other links</p></li>
				<!--<span class="glyphicon glyphicon-log-out"></span><a href="../../includes/logout.php"><p>Log out</p></a>-->
				<li><a href="../../includes/logout.php"><p>Log out</p></a></li>
				<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
			</ul>
		</footer>
    </body>
</html>
