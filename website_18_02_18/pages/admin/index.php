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
                            <img src='../../images/logo.png' width='300' height='100' alt=''>
                        </div>
        </header>
		<h3>Welcome to electago Admin Home Page</h3>

		<div class="card" style="width: 18rem;">
		  <img class="card-img-top" src="..." alt="Card image cap">
		  <div class="card-body">
		    <h5 class="card-title">Card title</h5>
		    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
		    <a href="#" class="btn btn-primary">Go somewhere</a>
		  </div>
		</div>
		<a href="elections.php"><button type="button" class="btn btn-secondary">Create/View Elections</button></a><p><p>
		<a href="results.php"><button type="button" class="btn btn-secondary">View Election Results</button></a><p><p>
		<a href="candidates.php"><button type="button" class="btn btn-secondary">Add/Edit Candidates</button></a><p><p>
		<a href="uploadKey.php"><button type="button" class="btn btn-secondary">Upload key</button></a><p></p>
		<a href="decryptScreen.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
		<a href="demographics.php"><button type="button" class="btn btn-secondary">View Demographics</button></a>

		<a href="../../includes/logout.php"><p>Log out</p></a>
	</body>

        <!--info here: logo, copyright, links, login as admin-->
        <div id='small_logo' class='media'>
            <img src='../../images/small_logo.png' width='100' height='35' alt=''>
        </div>
        <div class='media-body'>
        <ul class='list-inline pull right'>
            <li><a href='#'>Help</a></li>
            <li><p>Other links</p></li>
            <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
        </ul>
        </div>

    </footer>
    	</body>
</html>
