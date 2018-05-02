<?php
require_once('../../includes/functions.php'); 
include('../../includes/settings.php');

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");  
}

global $local; //Setting up database based on local variable
$servername = ""; //Set up connection variables
$dbname = "";
$dbusername = "";
$dbpassword = "";
$table = "election"; 

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

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Demographics</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="../../css/electago.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
</head>
			
	<body>
	<header class="container-fluid text-center">
		<div id="logo">
			<img src="../../images/logo.png" width="300" height="100" alt="">
		</div>
	</header>
		<h1>Voting Demographics</h1>
		
		<h3>Please select which Election you would like to view the demographics for:</h3>
		
		<?php
			try{
				$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
				if (!$conn){
					echo "Can't connect to the database.";
				}				

				$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql_select = 'SELECT electionID, electionName, electionDate FROM '.$table;
				$elections = array();
				$electionDates = array();
				
				foreach ($conn->query($sql_select) as $row) {
					$id = $row['electionID'];
					$name = $row['electionName'];
					$date = $row['electionDate'];
					$elections[$id] = $name;
					$electionDates[$id] = $date;
					
					echo '<a href="results.php?id='.$id.'"><button type="button" class="btn btn-secondary">'.$name.'</button></a><p></p>';
				}
				
				$_SESSION['elections'] = $elections;
				$_SESSION['electionDates'] = $electionDates;
			}
			catch(PDOException $e){
				echo $sql . "<br>" . $e->getMessage();
			}

			$conn = null;
		?>		
		
	</body>

	<footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
		</br>
		<ul>
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="index.php">Back</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
	</footer>

		<div id="small_logo">
		<img src="../../images/small_logo.png" width="100" height="35" alt="">
	</div>
</html>