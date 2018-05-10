<?php 
/*Noah Johnson C1649499*/

function redirect($page){ 
	header('Location: ' . $page); 
	exit(); 
} 

function debug($error){
	$_SESSION['debug'] = $error;
	redirect("debugger.php");
}	
?>