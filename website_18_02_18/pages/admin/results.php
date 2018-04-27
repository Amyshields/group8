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
	
	//Get total number of voters available to vote
	$sql_select = 'SELECT Username FROM voter';
	$voters = 0;
	foreach ($conn->query($sql_select) as $row) {
		$voters++;
	}
	
	$sql_select = 'SELECT candidateID FROM '.$table;
	$votes = array();
	$candidates = array();
	$voted = 0;
	
	foreach ($conn->query($sql_select) as $row) {
		$id = $row['candidateID'];
		$voted++;
		if (!isset($votes[$id])){
			$votes[$id] = 1;
			array_push($candidates,$id);
		}
		else{
			$votes[$id] = $votes[$id] + 1;
		}		
	}
	
	//Get turnout
	$turnout = round(($voted/$voters) * 100, 2);
	$novote = round(100-$turnout,2);
	//echo 'voters: '.$voters.' , voted: '.$voted;#
	echo $turnout;
	
	$sql_select = 'SELECT candidateID, candidateParty, candidateArea FROM candidate';
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
		
		//Put candidates into an array for the respective constituency (for seat calculation)
		if (!isset($area_votes[$area])){
			$area_votes[$area] = array();
			array_push($area_names, $area);		
		}
		array_push($area_votes[$area],$id); //adding candidate into constituency array to sort winner
		
		//Get total number of votes for each party for total party vote count
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
	
	//Find number of votes for each candidate in a constituency
	foreach ($area_names as $area_name){
		foreach ($area_votes[$area_name] as $area_candidates){ 
			if (!isset($area_scores[$area_name])){
				$area_scores[$area_name] = array();
			}

			if (isset($votes[$area_candidates])){
				$area_scores[$area_name][$area_candidates] = $votes[$area_candidates];
			}
		}	
	}
	
	//Find the highest scores for each constituency candidate group
	foreach ($area_names as $area_name){
		$scores = $area_scores[$area_name];
		$scores2 = $area_scores[$area_name];
		
		if (count($area_scores[$area_name]) > 0){
			//CODE FOR COIN FLIP
			/*$winner_ids = array_keys($scores2, max($scores2));
			foreach ($winner_ids as $a){
				echo 'candidate: (' . $candidate_parties[$a] . ') ' . $a . ': ' . $scores2[$a] . '</br>';
			}
			$winner = null;

			//Test to find multiple winners
			for ($i = 1; $i <= count($winner_ids)-1; $i++) {
				if ($scores2[$winner_ids[$i]] <  $scores2[$winner_ids[0]]){
					unset($scores2,$winner_ids[$i]); //not a winner or tied with winner
				}
			}*/ //END OF CODE FOR COIN FLIP

			/*if (count($scores2) > 1){
				$rand = rand(0,count($scores2)-1);
				$winner = array_keys($scores, max($scores))[$rand];
			}
			else{
				$winner = array_keys($scores, max($scores))[0];
			}*/
			//echo $winner . '</br>';

			$winner_id = array_keys($scores, max($scores))[0];
			$candidate_party = $candidate_parties[$winner_id];
			if (!isset($seats[$candidate_party])){
				$seats[$candidate_party] = 0;	
			}
			$seats[$candidate_party] = $seats[$candidate_party] + 1; //Adding a seat to the winning party
		}	
	}

	//Finding if there is a tie, and coin flipping to find a winner
	
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
				title: "Party"
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
				title: "Party"
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
				title: "Party"
			},
			axisY:{ 
				title: "Change (%)",
				suffix: "%"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "column",
				yValueFormatString: "0'% Votes'",
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

		var chart4 = new CanvasJS.Chart("chartContainer4", {
			animationEnabled: true,
			exportEnabled: true,
			axisX:{ 
				title: "Party"
			},
			axisY:{ 
				title: "Seats"
			},
			data: [              
			{
				// Change type to "doughnut", "line", "splineArea", etc.
				type: "pie",
				yValueFormatString: "0'%'",
				dataPoints: [
					<?php
						echo '{ label: "Voted",  y: '.$turnout.'},';
						echo '{ label: "No Vote",  y: '.$novote.'},';
					?>
				]
			}
			]
		});
		chart4.render();		
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
		
		<h2>Turnout</h2>
		<div id="chartContainer4" style="height: 300px; width: 600px;"></div>
		
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