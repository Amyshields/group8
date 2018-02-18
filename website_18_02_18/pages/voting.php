<?php
session_start();
if(!isset($_SESSION['logged_in'])){
   $_SESSION['error'] = "Please enter your National Insurance Number and Password";
   header("Location: ../index.php");  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Voting Page</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container-fluid">    
	<header class="container-fluid text-center">
	<h1>Electago - Voting Page</h1>
		<!--header image here-->
		<!---->
	</header>
<!--container class used in bootstrap to make a dynamic container of a fixed size-->
<div class="container">
	<form action="#.php">
		<h2>Local Election</h2>
		<p> Please select who you wish to vote for, for your constituency Cardiff North </p>
		<input type="radio" id="Choice1" name="choice"> <label for="Choice1"> Joe Bloggs </label><p><p>
		<input type="radio" id="Choice1" name="choice"> <label for="Choice1"> Jane Smith </label><p>
		<input type="radio" id="Choice1" name="choice"> <label for="Choice1"> Dan Scott </label><p>
		<input type="submit" class="btn btn-default" value="Vote" autofocus>
	</form>
</div>

<div class="container">
	<form action="#.php">
		<h2>National Election</h2>
		<p> Brexit, yes or no? </p>
			<input type="radio" id="Choice1" name="choice1" ><label for="choice1">  Yes </label><p><p>
			<input type="radio" id="Choice1" name="choice1"><label for="choice2">  No </label><p>
			<input type="submit" class="btn btn-default" value="Vote" autofocus>
	</form>
	
	<a href="../includes/logout.php"><p>Log out</p></a>
</div>

<footer class="container-fluid text-left">
	<!--info here: logo, copyright, links, login as admin-->
	<ul>
		<li><a href="#">Help</a></li>
		<li><p>Other links</p></li>
		<li><a href="#">Login as admin</a></li>
		<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
	</ul>
</footer>

</html>