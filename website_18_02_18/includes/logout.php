<?php 
/*Noah Johnson C1649499*/

session_start(); 

require_once('functions.php'); 
include('settings.php');

if (!isset($_SESSION['logged_in'])){ 
	redirect('login.php'); 
} 

// Kill session variables 
unset($_SESSION['logged_in']); 
unset($_SESSION['username']);
unset($_SESSION['constituency']); 	

// Destroy session 
session_destroy();

redirect('../index.php'); //back to login			  

?>