<?php
require_once('../../includes/functions.php'); 

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");  
}
?>


<!doctype html> <!--Max and Noah-->
<html>
	<head>
		<title>Admin Home Page</title>
		    <link href="../../css/admin.css" rel="stylesheet" type="text/css">
	</head>
			
	<body>
		<h1>Admin Home</h1>
		
		<h2>Election Demographics</h2>
		<p>Click on the election you wish to view the demographics for:</p>
		<p><a href="demographics.php">Local Election</a></p>
		<a href="demographics.php"><img src="../../images/local.gif" width="640" height="313" alt=""></a>
		
		<p><a href="demographics.php">National Election</a></p>
		<a href="demographics.php"><img src="../../images/national.png" width="320.25" height="427" alt=""></a>		
	
		<a href="../../includes/logout.php"><p>Log out</p></a>
	</body>
	
	<footer>Voting System 2018</footer>
</html>
