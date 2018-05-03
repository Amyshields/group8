<?php
/*Cyrus Dobbs C1529854*/
/*Dervla O'Brien C1642646*/
require_once('../../includes/functions.php'); 

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");  
}
?>
<!DOCTYPE html>
<html lang="en">
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
    <div class="container">
	<div class="container-fluid col-sm-offset-2">
		<div class="wrap">
        <div class="container" id="intro">
			<h1>Admin Upload Key Page</h1>
			<p>Upload your personal private key into the text field below.</p>
		</div>
        <form action="uploadScript.php" method="post">
            <label for="adminKeyInput">Your Admin Key:</label>
		    <input id="adminKeyInput" name="adminKeyInput" type="text"><br>
		    <input type="submit" class="btn btn-default" value="Upload" autofocus>
	    </form><br>
		<a href="index.php"><button type="button" class="btn btn-secondary">Go back</button></a>
		<a href="decryptScreen.php"><button type="button" class="btn btn-secondary">Decrypt Votes</button></a><p></p>
		<a href="../../includes/logout.php"><p>Log out</p></a>


      </div>
    </div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="adminHelp.html">Help</a></li>
        <li><a href="..\admin\index.php">Back</a></li>
        <li><a href="..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
</body>
</html>
