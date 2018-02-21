<?php 
/*Noah Johnson C1649499*/

session_start(); 

require_once('functions.php'); 

if (!isset($_SESSION['logged_in'])){ 
	redirect('login.php'); 
} 

$admin = isset($_SESSION['admin']); //checking if user logging out is an admin

//Kill neutral session variables
unset($_SESSION['logged_in']); 
unset($_SESSION['username']);

//Kill admin session variables
if ($admin){ 
	unset($_SESSION['admin']); 
}  	

//Kill voter session variables 
else{
	unset($_SESSION['constituency']);
}

//Destroy session 
session_destroy();

if ($admin){ 
	redirect('../pages/admin-login.php'); //back to admin login	
}

redirect('../index.php'); //back to login			  

?>