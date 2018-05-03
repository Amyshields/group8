<?php
/*Noah Johnson C1649499*/

require_once('functions.php');
require_once('password.php');
include('settings.php');

session_start();

//Google Recaptcha response to stop registration spam bots
if ($local == false){
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$privatekey = "6Lev_1YUAAAAAMK9Y9j8yI2rUR01LZRE8sNiHMxp";

	$response = file_get_contents($url."?secret=".$privatekey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$data = json_decode($response);

	if(!isset($data->success) OR $data->success!=true){
		$_SESSION['error'] = "Recaptcha incomplete";
		redirect('../index.php');
	}
}

function login($username,$password){
	global $local; //Setting up database based on local variable
	$servername = ""; //Set up connection variables
    $dbname = "";
	$dbusername = "";
	$dbpassword = "";
	$table = "voter";

	if ($local == true){ //Setting up variables for local connection
		global $lservername;
		global $ldbname;
		global $ldbusername;
		global $ldbpassword;
		$servername = $lservername;
		$dbname = $ldbname;
		$dbusername = $ldbusername;
		$dbpassword = $ldbpassword;
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
			redirect('../index.php');
		}

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql_select = "SELECT * FROM ".$table." WHERE username=:username";
		$query = $conn->prepare($sql_select);

		$query->execute(array(':username' => $username));

		$num_rows = $query->rowCount();

		if ($num_rows > 0){

			foreach ($query as $row) {
				$dbuname = $row['Username'];
				$dbpw = $row['Password'];
				$dbconstituency = $row['Constituency'];
			}

			if(($username==$dbuname)&&password_verify($password, $dbpw)){
				if (isset($_SESSION['error'])){
					unset($_SESSION['error']);
				}

				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $username;
				$_SESSION['constituency'] = $dbconstituency;
				redirect('../pages/dashboard.php');
			}

			else{
				$_SESSION['error'] = "Your National Insurance Number or Password is incorrect";
				redirect('../index.php');
			}
		}
		else{
			$_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
			redirect('../index.php');
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
{
	$_SESSION['error'] = "Please enter your National Insurance Number and Password";
	redirect('../index.php');
}
#get errors to show
?>

