<?php
require_once('../../includes/functions.php'); 
include('../../includes/settings.php');

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");  
}

if (!isset($_SESSION['elections'])){
	redirect("demographics.php");
}

if (!isset($_GET['id'])){
	redirect("demographics.php");	
}

$id = $_GET['id'];
$name = $_SESSION['elections'][$id];
$name2 = strtolower(str_replace(' ', '', $name));

global $local; //Setting up database based on local variable
$servername = ""; //Set up connection variables
$dbname = "";
$dbusername = "";
$dbpassword = "";
$table = $name2; //If can't connect to table, first letter needs to be uppercase.
$table2 = "candidate";

if ($local == true){ //Setting up variables for local connection
    global $lservername;
    global $ldbname;
    global $ldbusername;
    global $ldbpassword;
    $servername = $lservername;
    $dbname = $ldbname;
    $dbusername = $ldbusername;
    $dbpassword = $ldbpassword;
    $table = $name2;
	//$table2 = "candidate";
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
		echo "Can't connect to the database.";
	}				

	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql_select = 'SELECT candidateID FROM '.$table;
	$votes = array();
	$candidates = array();
	$s = "";
	
	foreach ($conn->query($sql_select) as $row) {
		$id = $row['candidateID'];
		if (!isset($votes[$id])){
			$votes[$id] = 1;
			array_push($candidates,$id);
		}
		else{
			$votes[$id] = $votes[$id] + 1;
		}
	}
	
	$sql_select = 'SELECT candidateID, candidateParty FROM '.$table2;
	$parties = array();
	$party_votes = array();
	
	foreach ($conn->query($sql_select) as $row) {
		$id = $row['candidateID'];

		if (isset($votes[$id])){
			$party = $row['candidateParty'];
			if (!isset($party_votes[$party])){
				$party_votes[$party] = $votes[$id];
				array_push($parties,$party);
			}
			else{
				$party_votes[$party] = $party_votes[$party] + $votes[$id];
			}
		}
	}
	
}
catch(PDOException $e){
	echo $sql . "<br>" . $e->getMessage();
}

$conn = null;


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
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
	<script type="text/javascript">

	window.onload = function () {
		var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			exportEnabled: true,
			axisX:{ 
				title: "Constituency"
			},
			axisY:{ 
				title: "Votes"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "column",
				dataPoints: [
					<?php
					foreach ($parties as $party){
						echo '{ label: "'.$party.'",  y: '.$party_votes[$party].'},';
					}
					?>
				]
			}
			]
		});
		chart.render();
		
		var chart2 = new CanvasJS.Chart("chartContainer2", {
			animationEnabled: true,
			exportEnabled: true,
			axisX:{ 
				title: "Constituency"
			},
			axisY:{ 
				title: "Seats"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "bar",      
				dataPoints: [
					<?php
					foreach ($parties as $party){
						echo '{ label: "'.$party.'",  y: '.$party_votes[$party].'},';
					}
					?>
				]
			}
			]
		});
		chart2.render();
		
		var chart3 = new CanvasJS.Chart("chartContainer3", {
			animationEnabled: true,
			exportEnabled: true,
			axisX:{ 
				title: "Constituency"
			},
			axisY:{ 
				title: "Change (%)",
				suffix: "%"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "column",
				yValueFormatString: "0'%'",
				dataPoints: [
					{ label: "Labour",  y: 10 },
					{ label: "Conservatives",  y: -10 },
					{ label: "Plaid Cymru",  y: -20 },
					{ label: "Green",  y: 5 }
				]
			}
			]
		});
		chart3.render();		
	}
	</script>
</head>
			
	<body>
	<header class="container-fluid text-center">
		<div id="logo">
			<img src="../../images/logo.png" width="300" height="100" alt="">
		</div>
	</header>
		<h1><?php echo $name; ?> Voting Demographics</h1>
		
		<h2>Final Voting Results</h2>
		<div id="chartContainer" style="height: 300px; width: 600px;"></div>

		<h2>Individual Party Results by Seat</h2>
		<div id="chartContainer2" style="height: 300px; width: 600px;"></div>
		
		<h2>Change from Previous Year</h2>
		<div id="chartContainer3" style="height: 300px; width: 600px;"></div>
	</body>

	<footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
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