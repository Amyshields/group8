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
        <img src="images/logo.png" width="300" height="100" alt="">
      </div>
    </header>
    <div class="container">
    <div class="container-fluid col-sm-offset-2">
      <div class="wrap">
		<h1>Voting Demographics</h1>
		<br>
      <!--Error box here?-->	
		<p>Please select an election you would like to view the demographics for:</p>
		<br>
		<div class="col-sm-10">
		<?php
			try{
				$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
				if (!$conn){
					echo  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Couldn't connect to the database</div>";
        			#echo "Can't connect to the database.";
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
					echo '<a href="results.php?id='.$id.'" id="selection" class="btn btn-lg btn-block btn-success md-3"><p><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;'.$name.'</p></a> <br>';
				}
				
				$_SESSION['elections'] = $elections;
				$_SESSION['electionDates'] = $electionDates;
			}
			catch(PDOException $e){
				echo $sql . "<br>" . $e->getMessage();
			}

			$conn = null;
		?>
		<br>
		<a href="../admin/index.php" id="selection" class="btn btn-lg btn-block btn-info md-3"><p><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go Back </p></a>	
		<br>
		</div>
	  </div>
    </div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="adminHelp.html">Help</a></li>
        <li><a href="..\admin\index.php">Back</a></li>
        <li><a href="..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
</html>