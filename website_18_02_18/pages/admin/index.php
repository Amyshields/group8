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
		<h3>Welcome to electago Admin Home Page</h3>
<<<<<<< HEAD
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
=======

		<div class="btn-group-vertical">
			<a href="elections.php"><button type="button" class="btn btn-secondary">Create/View Elections</button></a><p><p>
			<a href="results.php"><button type="button" class="btn btn-secondary">View Election Results</button></a><p><p>
			<a href="candidates.php"><button type="button" class="btn btn-secondary">Add/Edit Candidates</button></a><p><p>
			<a href="uploadKey.php"><button type="button" class="btn btn-secondary">Upload key</button></a><p></p>
			<a href="decryptVotes.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
			<a href="demographics.php"><button type="button" class="btn btn-secondary">View Demographics</button></a>
		</div> 

		<a href="../../includes/logout.php"><button type="button" class="btn btn-alert">Log out</button></a>


	<footer class='container-fluid'>
        <!--info here: logo, copyright, links, login as admin-->

        <div id='small_logo' class='media'>
            <img src='images/small_logo.png' width='100' height='35' alt=''>
        </div>
        <div class='media-body'>
        <ul class='list-inline pull right'>
            <li><a href='#'>Help</a></li>
            <li><p>Other links</p></li>
            <li><a href='pages/index.php'>Back to voter login</a></li>
            <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
        </ul>
        </div>
    </footer>
    	</body>
>>>>>>> e4f07d613128ba55ec0fd5127d2e193b6e2a41f8
</html>
