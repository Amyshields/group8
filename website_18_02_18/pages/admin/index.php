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
	<div class="container-fluid col-sm-offset-1 col-sm-10">
		<div class="wrap">
        <div class="container" id="intro">
			<h3>Welcome to the Electago Administrator Home Page</h3>
		</div>
	</div>
	<div class="container-fluid">

			<a href="elections.php" class="btn btn-lg btn-block btn-warning"  id='selection'><span class="glyphicon glyphicon-plus"></span> &nbsp;Create New Election</a>

			<a href="elections.php" class="btn btn-lg btn-block  btn-warning"  id='selection'><span class="glyphicon glyphicon-list"></span> &nbsp;Current Elections</a>

			<a href="demographics.php" class="btn btn-lg btn-block  btn-warning"  id='selection'><span class="glyphicon glyphicon-check"></span> &nbsp;Election Results and Demographics</a>

			<a href="candidates.php" class="btn btn-lg btn-block  btn-warning"  id='selection'><span class="glyphicon glyphicon-user"></span> &nbsp;Add or Edit Candidates</a>

			<a href="uploadKey.php" class="btn btn-lg btn-block  btn-warning"  id='selection'><span class="glyphicon glyphicon-upload"></span> &nbsp;Upload Private Key</a>

			<a href="candidates.php" class="btn btn-lg btn-block  btn-warning"  id='selection'><span class="glyphicon glyphicon-lock"></span> &nbsp;Decrypt Votes</a>


			<br>

			<!--a href="elections.php"><button type='submit' id='selection'  class='btn btn-warning btn-lg submit' name='selection'>Create View Elections</button></a>
			<a href="elections.php"><button type='submit' id='selection'  class='btn btn-warning btn-lg submit' name='selection'>View Results and Demographics</button></a>

			<br-->


			<a href="../../includes/logout.php" class="btn btn-lg btn-block btn-info"><p><span class="glyphicon glyphicon-log-out"></span> Log out</p></a>
		</div>
	<footer>
        <!--info here: logo, copyright, links, login as admin-->
        <div id='small_logo' class='media'>
            <img src='../../images/small_logo.png' width='100' height='35' alt=''>
        </div>
        <div class='media-body'>
        <ul class='list-inline pull right'>
            <li><a href="adminHelp.html">Help</a></li>
            <li><a href="../includes/logout.php">Log out</a></li>
            <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
        </ul>
        </div>
    </footer>
</div>
</div>
    </body>
</html>
