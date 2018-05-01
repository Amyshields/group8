<?php
require_once('../includes/functions.php');

session_start();

if(isset($_SESSION['logged_in'])){
   redirect("../index.php");
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
	<link href="../css/electago.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<header class="container-fluid text-center">
		<div id="logo">
			<img src="../images/logo.png" width="300" height="100" alt="wtf">
		</div>
	</header>
	<!--container class used in bootstrap to make a dynamic container of a fixed size-->
	<div class="container">
		<div class="container-fluid col-sm-offset-2">
		<div class="wrap">
			<h3 >Welcome to electago, where you can elect on the go.</h3>
			<div class="col-sm-8" id="intro">
				<p>This page is only for administrator use. If you are not an administrator click <a href="/index.php">here</a> 
					to return to the normal login page.</p>
				<p>Be careful when logging in that you do not save your login details to your browser as they are highly sensitive.</p>
			</div>
			<!--/div-->
			<div class="col-sm-2">
			</div>
			<div class="col-sm-8">
				<form action="../includes/login-admin.php" method="post">
					<h2>Administrator Login:</h2>
					<div class="form-group">
						<!--The format of the number is two prefix letters, six digits, and one suffix letter. The example used is typically QQ123456C. -->
						<label for="username"> Username: </label>
						<input id="username" type="text" name="username" class="form-control col-sm-6" value="Your Administrator Username" onfocus="if (this.value=='Your Administrator Username') this.value='';"><br>
					</div>

					<div class="form-group">
						<!--Password example formatted like the sample passwords we will produce-->
						<label for="passwordInput"> Password: </label>
						<input type="password" class="form-control col-sm-6" name="password" id="passwordInput" placeholder="*********"><br>
						<!-- An element to toggle between password visibility -->
						<div class="form-check" id="passwordCheckbox">
							<input class="form-check-input" id="gridCheck" type="checkbox" onclick="showPass()">
							<label class="form-check-label" for="gridCheck"><small>Show Password</small></label>
						</div>
					</div>

					<input type="submit" class="btn btn-warning pull-right" id="login" value="Login" autofocus>
				</form>
				<div>
					<p> Or click <a href="../index.php">here</a> to login as a voter. </p>
				</div>
				<div id="info">
					<br /> <p> Election administrators log in above using the username and password given to them. You should have recieved two seperate private letters containing this information. If you have not recieved this letter please contact your local electoral office.</p>
				</div>
			</div>
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
				<img src="../images/small_logo.png" width="100" height="35" alt="">
			</div>
			<div class="media-body">
			<ul class="list-inline footerLinks">
				<li><a href="#">Help</a></li>
				<li><a href="../index.php">Login as voter</a></li>
				<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
			</ul>
			</div>
		</footer>
		</div>
	</div>
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
