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
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<header class="container-fluid text-center">
		<img src="../logo.png">
		<h2>Voting System Admin Section</h2>
		<!--should we have an official name?-->
		<!--header image here-->
		<!---->
	</header>
	<!--container class used in bootstrap to make a dynamic container of a fixed size-->
	<div class="container">
		<div class="container-fluid">    
		<div class="col-sm-2">
		</div>
		<div class="col-sm-8">
			<form action="../includes/login-admin.php" method="post">
				<h2>Login here</h2>
				
				<div class="form-group">
					<!--The format of the number is two prefix letters, six digits, and one suffix letter. The example used is typically QQ123456C. -->
					<label for="email"> Username: </label>
					<input type="text" name="username" class="form-control col-sm-6" value="ladmin" onfocus="if (this.value=='ladmin') this.value='';"><br>
				</div>
				
				<div class="form-group">
					<!--Password example formatted like the sample passwords we will produce-->
					<label for="password"> Password: </label>
					<input type="password" class="form-control col-sm-6" name="password" value="*********" onfocus="if (this.value=='*********') this.value='';"><br>
					<!-- An element to toggle between password visibility -->
					<input type="checkbox" onclick="showPass()">Show Password 
				</div>
				
				<input type="submit" class="btn btn-default" value="Login" autofocus>
			</form>
		</div>
			<div class="col-sm-2 sidenav">
				<div class="well">
					<p></p>
				</div>
			</div>
		</div>
		
		<?php
			if (isset($_SESSION['error'])){
				echo $_SESSION['error'];
				unset($_SESSION['error']); 
			}			
		?>
	</div>

	<footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
		<ul>
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="../index.php">Login as voter</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
	</footer>

<script>
function showPass() {
    var x = document.getElementById("myInput");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>
</body>
</html>