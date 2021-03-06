<!DOCTYPE html>
<html lang="en">
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
</head>

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

if (!isset($_SESSION['electionDates'])){
	redirect("demographics.php");
}

if (!isset($_GET['id'])){
	redirect("demographics.php");
}

$id = $_GET['id'];
if (!isset($_SESSION['elections'][$id])){
	redirect("demographics.php");
}

$name = $_SESSION['elections'][$id];
$name2 = strtolower(str_replace(' ', '', $name));
$date = $_SESSION['electionDates'][$id];

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

date_default_timezone_set('Europe/London');
$today = date("Y-m-d H:i:s");
$electdate = $date . " 00:00:00";

if ($today > $electdate){
	try{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
		if (!$conn){
			echo "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Couldn't connect to the database</div>";
		}

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


		$sql_select = "SELECT electionType FROM election WHERE electionID = $id";
		foreach ($conn->query($sql_select) as $row) {
			$type = $row[0];
		}
		if ($type == "REF"){
			$sql_select = "SELECT COUNT(candidateID) FROM $table WHERE candidateID = 1";
			foreach ($conn->query($sql_select) as $row) {
				$votedYes = $row[0];
			}
			$sql_select = "SELECT COUNT(candidateID) FROM $table WHERE candidateID = 0";
			foreach ($conn->query($sql_select) as $row) {
				$votedNo = $row[0];
			}
			$sql_select = "SELECT COUNT(candidateID) FROM $table";
			foreach ($conn->query($sql_select) as $row) {
				$voted = $row[0];
			}

			$sql_select = "SELECT COUNT(Username) FROM voter";
			foreach ($conn->query($sql_select) as $row) {
				$turnout = round(($voted/$row[0]) * 100, 2);
				$novote = 100 - $turnout;
			}
			$novote = round(100-$turnout,2);
		} else {
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

			if (!is_numeric($candidates[0])){
				echo '<h2>Results are still encrypted for '.$name.'</h2>';
				exit();
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

	}
	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}

	$conn = null;

}
else{
	echo '<h2>Demographics for '.$name.' can only be shown once the election has finished ('.$date.')</h2>';
	exit();
}
?>

<body>
	<header class="container-fluid text-center">
      <div id="logo">
        <img src="../../images/logo.png" width="300" height="100" alt="">
      </div>
    </header>
    <div class="container">
   	<h1><?php echo $name; ?> Voting Demographics</h1>
    <div class="container-fluid col-sm-offset-2">
      <div class="wrap">
      <!--Error box here?-->
      <div class="col-sm-8" id="intro">
      	<br>
		<p>Below are the final results of the <?php echo $name; ?>. The demographics displayed include the election results, the results for each party, the overall turnout and the change from previous years. Click on the top right hand corner of a graph to save the image to your computer.</p>
      <a href="demographics.php" class="btn btn-lg btn-info pull-right"> <span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go back</a>
	</div>
      <br>
      <div class="col-sm-12">
      <div class="container-fluid" style="min-width: 600px;">
		<h2>Final Voting Results</h2>
		<div id="chartContainer" style="height: 300px; width: 600px;"></div>
		<div class="fptpCharts">
		<h2>Individual Party Results</h2>
			<div id="chartContainer2" style="height: 300px; width: 600px;"></div>
		</div>
		<h2>Turnout</h2>
		<div id="chartContainer4" style="height: 300px; width: 600px;"></div>

		<div class="fptpCharts">
			<h2>Change from Previous Year</h2>
			<div id="chartContainer3" style="height: 300px; width: 600px;"></div>
		</div>
	  </div>
	  <br>
	</div>
	<br>
	</div>
    </div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="../../images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="..\..\adminHelp.html">Help</a></li>
        <li><a href="demographics.php">Back</a></li>
        <li><a href="..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
	</body>

<script type="text/javascript">
	window.onload = function () {

	var electionType = "<?php echo $type; ?>";

	if (electionType == "FPTP"){
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
	} else {

	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		exportEnabled: true,
		axisX:{
			title: "Yes or No"
		},
		axisY:{
			title: "Votes"
		},
		data: [
		{
			// Change type to "doughnut", "line", "splineArea", etc.
			type: "column",
			yValueFormatString: "0' Votes'",
			dataPoints: [
				<?php

				if (isset($votedYes)){
					echo '{ label: "Yes",  y: '.$votedYes.'},';
					echo '{ label: "No",  y: '.$votedNo.'},';
				}

				?>
			]
		}
		]
	});
	chart.render();

	var chart4 = new CanvasJS.Chart("chartContainer4", {
		animationEnabled: true,
		exportEnabled: true,
		axisX:{
			title: "Voted"
		},
		axisY:{
			title: "Not Voted"
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

	var fptpCharts = document.getElementsByClassName("fptpCharts"); //divsToHide is an array
		for(var i = 0; i < fptpCharts.length; i++){
			fptpCharts[i].style.visibility = "hidden";
			fptpCharts[i].style.display = "none";
		}
	}
	}
</script>

</html>


