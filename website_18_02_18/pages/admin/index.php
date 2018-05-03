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
		  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		</head>


	<body>
	<header class='container-fluid text-center'>
        <div id='logo'>
            <img src='../../images/logo.png' width='300' height='100' alt=''>
        </div>
    </header>
    <div class="container">
	<div class="container-fluid col-sm-offset-2">
		<div class="wrap">
        <div class="container" id="intro">
			<h3>Welcome to electago Admin Home Page</h3>
		</div>
	</div>
	<div class="container">
		<div class="card-deck">
			<div class="card bg-dark mb-3 col-sm-3" style="background: white;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Create/ View Elections</h5>
			    <p class="card-text">Click here to create a new election and view all current elections.</p>
			    <a href="elections.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>

			<div class="card bg-dark mb-3" style="max-width: 18rem; background: white;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Add/Edit Candidates</h5>
			    <p class="card-text">Click here to add edit or delete candidates from an election.</p>
			    <a href="candidates.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>

			<div class="card bg-dark mb-3">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Upload Key</h5>
			    <p class="card-text">Click here to upload your personal admin private key.</p>
			    <a href="uploadKey.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>

			<div class="card bg-dark mb-3" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">Decrypt Votes</h5>
			    <p class="card-text">Click here to decrypt the election votes so that the results can be tallied. This can not be done until every admin has uploaded their private key.</p>
			    <a href="uploadKey.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>

			<div class="card bg-dark mb-3" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">View Election Results</h5>
			    <p class="card-text">Click here to view the final results to the election. <br/> Please ensure the results have been successfully decrypted first.</p>
			    <a href="results.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>

			<div class="card bg-dark mb-3" style="width: 18rem;">
			  <img class="card-img-top" src="..." alt="Card image cap">
			  <div class="card-body">
			    <h5 class="card-title">View Demographics</h5>
			    <p class="card-text">Click here to view detailed election demographics for this and previous years. <br/> An election will not appear in the demographics section until voting has closed.</p>
			    <a href="results.php"><button type="button" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-right"></i></button></a>
			  </div>
			</div>
		</div>
		</div>

			<a href="elections.php"><button type="button" class="btn btn-secondary">Create/View Elections</button></a><p></p>
			<a href="results.php"><button type="button" class="btn btn-secondary">View Results and Demographics</button></a><p></p>
			<a href="candidates.php"><button type="button" class="btn btn-secondary">Add/Edit Candidates</button></a><p></p>
			<a href="uploadKey.php"><button type="button" class="btn btn-secondary">Upload key</button></a><p></p>
			<a href="decryptScreen.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
			<!--a href="demographics.php"><button type="button" class="btn btn-secondary">View Demographics</button></a-->

			<a href="../../includes/logout.php"><p>Log out</p></a>
		</div>
	</div>
	<footer>
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
	</div>
    </body>
</html>
