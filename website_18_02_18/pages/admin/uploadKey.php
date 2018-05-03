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
		<h1 class="pull-left">Admin Upload Key Page</h1>
		<br>
		<div class="wrap">
        <div class="container" id="intro">
        	<br>
			<p class= "pull-left col-sm-8">Upload your personal private key into the text field below.</p>
			<!--a href="index.php" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go back</a-->
		</div>
		<div class="col-sm-8">
        <form action="uploadScript.php" method="post">
			<div class="form-group">
            	<label for="adminKeyInput">Your Admin Key:</label>
            	<div class="input-group">
		    		<input id="adminKeyInput" name="adminKeyInput" type="text" class="form-control input-lg">
		    		<span class="input-group-btn">
		    			<button class="btn btn-lg btn-info"><span class="glyphicon glyphicon-upload"></span> Upload</button>
		    		</span>
		    	</div>
		    </div>
	    </form><br>
		</div>
		<div class="col-sm-8">
			<p>Click below when all the admin keys have been uploaded.</p>
			<a href="decryptScreen.php" class="btn btn-lg btn-warning center-block"><span class="glyphicon glyphicon-check"></span> &nbsp; Decrypt Votes</a>
	    	<br>
	    	<p>Or go back to the admin dashboard.</p>
	    	<a href="../admin/index.php" class="btn btn-lg btn-primary center-block"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go Back</a>
	    	<br>
		</div>
      </div>    
	</div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="..\..\adminHelp.html">Help</a></li>
        <li><a href="..\admin\index.php">Back</a></li>
        <li><a href="..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
</body>
</html>
