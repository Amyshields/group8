<?php
require_once('includes/functions.php');

session_start();

if(isset($_SESSION['admin'])){
	redirect("pages/admin/index.php");
}

if(isset($_SESSION['logged_in'])){
   redirect("pages/voting.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Home Page</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/electago.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<header class="container-fluid text-center">
		<div id="logo">
			<img src="images/logo.png" width="300" height="100" alt="">
		</div>
	</header>
	<!--container class used in bootstrap to make a dynamic container of a fixed size-->
	<div class="container">
		<div class="container-fluid col-sm-offset-2">
		<h3 >Welcome to electago, where you can elect on the go.</h3>
		<div class="col-sm-8" id="intro">
			<p>Use this service to login and vote.</p>
			<br /> <p>You can use this service to vote for local and national elections. If you make a voting choice you can also login here again to change that vote as long as it is before the election's closing deadline. </p>
		</div>
		<div class="col-sm-2">
		</div>
		<div class="col-sm-8">
			<form action="includes/login.php" method="post">
				<h2>Login here:</h2>
				<div class="form-group">
					<!--The format of the number is two prefix letters, six digits, and one suffix letter. The example used is typically QQ123456C. -->
					<label for="email"> National Insurance Number: </label>
					<input type="text" name="username" class="form-control col-sm-6" value="QQ123456C" onfocus="if (this.value=='QQ123456C') this.value='';"><br>
				</div>

				<div class="form-group">
					<!--Password example formatted like the sample passwords we will produce-->
					<label for="password"> Password: </label>
					<input type="password" class="form-control col-sm-6" name="password" id="passwordInput" placeholder="*********"><br>
					<!-- An element to toggle between password visibility -->
					<input type="checkbox" onclick="showPass()">Show Password
				</div>
				<input type="submit" class="btn btn-warning pull-right" id="login" value="Login" autofocus>
			</form>
			<div>
				<p> Or click <a href="https://www.gov.uk/register-to-vote">here</a> to register to vote. </p>
			</div>
			<div id="info">
				<br /> <p> To use this service you must have first registered to vote. When you register you can expect to be sent a private letter containing the password you should use here. If you have not recieved this letter please contact your local electoral office.</p>
			</div>
		</div>

		<!--
			<div class="col-sm-2 sidenav">
				<div class="well">
					<p></p>
				</div>
			</div>
		</div> -->

		<?php
			if (isset($_SESSION['error'])){
				echo $_SESSION['error'];
				unset($_SESSION['error']);
			}
		?>
	</div>

	<footer class="container-fluid">
		<!--info here: logo, copyright, links, login as admin-->

		<div id="small_logo" class="media">
			<img src="images/small_logo.png" width="100" height="35" alt="">
		</div>
		<div class="media-body">
		<ul class="list-inline pull right">
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="pages/admin-login.php">Login as admin</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
		</div>
	</footer>

<script>
function showPass() {
    var x = document.getElementById("passwordInput");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>
</body>
</html>
