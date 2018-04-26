<?php
/*Noah Johnson C1649499*/

require_once('functions.php'); 
require_once('password.php');
include('settings.php');

session_start();

function login($username,$password){	
	global $local; //Setting up database based on local variable
	$servername = ""; //Set up connection variables
    $dbname = "";
	$dbusername = "";
	$dbpassword = "";
	$table = "admin";
	
	if ($local == true){ //Setting up variables for local connection
		global $lservername;	
		global $ldbname;
		global $ldbusername;
		global $ldbpassword;
		$servername = $lservername;
		$dbname = $ldbname;
		$dbusername = $ldbusername;
		$dbpassword = $ldbpassword;		
		$table = "admin"; //Fix for wamp server importing tables names as all lowercase
	}
	else{ //Setting up variables for online connection
		global $oservername;	
		global $odbname;
		global $odbusername;
		global $odbpassword;
		$servername = $oservername;
		$dbname = $odbname;
		$dbusername = $odbusername;
		$dbpassword = $odbpassword;
	}
	
	try{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
		if (!$conn){
			$_SESSION['error'] = "Couldn't connect to the database";
			redirect('../pages/admin-login.php');
		}				

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
		$sql_select = "SELECT * FROM ".$table." WHERE adminUsername=:username";
		$query = $conn->prepare($sql_select);
		
		$query->execute(array(':username' => $username));
		
		$num_rows = $query->rowCount();
		
		if ($num_rows > 0){
		
			foreach ($query as $row) {
				$dbuname = $row['adminUsername'];
				$dbpw = $row['adminPassword'];		
			}
			
			if(($username==$dbuname)&&($password==$dbpw)){ //password_verify($password, $dbpw)
				if (isset($_SESSION['error'])){
					unset($_SESSION['error']);
				}
				
				$_SESSION['logged_in'] = true; 	
				$_SESSION['admin'] = true; 				
				$_SESSION['username'] = $username;
				redirect('../pages/admin/index.php');					
			}
			
			else{
				$_SESSION['error'] = "Your Username or Password is incorrect";
				redirect('../pages/admin-login.php');				
			}
		}
		else{
			$_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
			redirect('../pages/admin-login.php');		
		}
	}
	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}

	$conn = null;	
}

$username = $_POST['username'];
$password = $_POST['password'];

if($username&&$password)
{	
	login($username,$password);	
}
else
	$_SESSION['error'] = "Please enter your Username and Password";
	redirect('../pages/admin-login.php');
?>