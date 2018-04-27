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
	
	$sql_select = 'SELECT candidateID, candidateParty, candidateArea FROM '.$table2;
	$parties = array();
	$party_votes = array();
	$area_votes = array();
	$area_names = array();
	$seats = array();
	$candidate_parties = array();
	
	foreach ($conn->query($sql_select) as $row) {
		$id = $row['candidateID'];
		$area = $row['candidateArea'];		
		$party = $row['candidateParty'];
		$candidate_parties[$id] = $party;
		
		//Get total number of seats won for each party
		if (!isset($area_votes[$area])){
			$area_votes[$area] = array();
			array_push($area_names, $area);		
		}
		array_push($area_votes[$area],$id); //adding candidate into constituency array to sort winner
		//echo $area.': '.$id,'</br>';
		
		//Get total number of votes for each party
		if (isset($votes[$id])){			
			if (!isset($party_votes[$party])){
				$party_votes[$party] = $votes[$id];
				array_push($parties,$party);
			}
			else{
				$party_votes[$party] = $party_votes[$party] + $votes[$id];
			}
		}
	}
	
	$area_scores = array();
	$area_winners = array();
	
	foreach ($area_names as $area_name){ //for every area name in the area names array
		foreach ($area_votes[$area_name] as $area_candidates){ 
			if (!isset($area_scores[$area_name])){
				$area_scores[$area_name] = array();
			}

			if (isset($votes[$area_candidates])){
				$area_scores[$area_name][$area_candidates] = $votes[$area_candidates];
			}
		}
		/*foreach($area_scores[$area_name] as $area_score){
			echo $area_score;	
		}*/		
	}
	
	foreach ($area_names as $area_name){
		$scores = $area_scores[$area_name];
		
		if (count($area_scores[$area_name]) > 0){
			$winner_id = array_keys($scores, max($scores))[0];
			$candidate_party = $candidate_parties[$winner_id];
			if (!isset($seats[$candidate_party])){
				$seats[$candidate_party] = 0;	
			}
			$seats[$candidate_party] = $seats[$candidate_party] + 1; //Finally adding the winning seat to the results
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
				title: "Seats"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "column",
				yValueFormatString: "0' Seat(s)'",
				dataPoints: [
					<?php
					foreach ($parties as $party){
						if (isset($seats[$party])){
							echo '{ label: "'.$party.'",  y: '.$seats[$party].'},';
						}
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
				title: "Votes"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "bar", 
				yValueFormatString: "0' Vote(s)'", 				
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

		<h2>Individual Party Results</h2>
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