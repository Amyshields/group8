<?php
/*Cyrus Dobbs C1529854*/
require_once('../includes/functions.php');
include('../includes/settings.php');

session_start();

if (isset($_SESSION['admin'])){
	redirect("../index.php");
}

if(!isset($_SESSION['logged_in'])){
   $_SESSION['error'] =  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Please enter your National Insurance Number and Password</div>";
    #$_SESSION['error'] = "Please enter your National Insurance Number and Password";
   header("Location: ../index.php");
}

$votedString = "";
if(isset($_GET['voted'])){
    $votedString = "<div class='alert alert-success alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong> Voted!</strong> Vote Recorded.
                    </div>";

    #"Vote recorded.";
}

$noSelectionString = "";
if(isset($_GET['noSelection'])){
    $noSelectionString = "<div class='alert alert-warning alert-dismissible'>
                         <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong> Select an option!</strong> No vote was recorded. Next time, click a radio button to select your choice.
                    </div>";

    #"No vote was recorded. Next time, click a radio button to select your choice.";
}

global $local; //Setting up database based on local variable
$servername = ""; //Set up connection variables
$dbname = "";
$dbusername = "";
$dbpassword = "";
$userConstituency = $_SESSION['constituency'];

if ($local == true){ //Setting up variables for local connection
    global $lservername;
    global $ldbname;
    global $ldbusername;
    global $ldbpassword;
    $servername = $lservername;
    $dbname = $ldbname;
    $dbusername = $ldbusername;
    $dbpassword = $ldbpassword;
    $table = "candidate"; //Fix for wamp server importing tables names as all lowercase
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
        $_SESSION['error'] ="<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Incorrect Oops!</strong> Couldn't connect to the database.
                        </div>";
        #$_SESSION['error'] = "Couldn't connect to the database";
        redirect('../index.php');
    }

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_selectElections = "SELECT * FROM election WHERE (electionArea='National' OR electionArea=:userConstituency) AND isEncrypted=1";

    $query = $conn->prepare($sql_selectElections);

    $query->execute(array(':userConstituency' => $userConstituency));

    $num_rows = $query->rowCount();

    date_default_timezone_set('Europe/London');
    $today = date("Y-m-d H:i:s");


    $activeElectionCount = 0;

    if ($num_rows > 0){

        $elections = array();
        
        foreach ($query as $row) {

            $date = $row['electionDate'];
            $electDate = $date . " 00:00:00";

            if ($electDate > $today){
                
                $activeElectionCount++;

                $electionID = $row['electionID'];
                $electionDisplayName = $row['electionDisplayName'];
                // $electionName = $row['electionName'];
                // $electionType = $row['electionType'];
                // $electionArea = $row['electionArea'];
                // $electionDate = $row['electionDate'];

                $thisElection = array($electionID, $electionDisplayName);
                array_push($elections, $thisElection);
            }
        }
    }
    else{
        $noElectionString = "<div class='alert alert-warning'>
                        <strong> You have no elections.</strong>
                    </div>>";

    #"You have no elections.";
    }
}
catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="../css/electago.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<header class="container-fluid text-center">
        <div id="logo">
            <img src="../images/logo.png" width="300" height="100" alt="">
        </div>
	</header>
    <div class="container">
        <div class="container-fluid">
            <div class="wrap">
            <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                <h1>Voting Dashboard</h1>
                <h2><?php echo $votedString;?></h2>
                <h2><?php echo $noSelectionString;?></h2>
                <div id="intro">
                    <p>Below you should see a list of elections you are eligible to vote for at this time.</p>
                    <p>Select the name of the election you want to vote in next to be taken to your online ballot. 
                    <br />Please take your time to read over your selection after voting to make sure the highlighted selection is correct and then click the "vote" button to return to this page.</p>
                </div>
                <div class="col-sm-2">
                </div>
                <div class="col-sm-12">
                	<form action="voting.php" method="post">
                        <!--posting selection-->
                        <div class="form-group text-center">
                            <label for="electionSelection">Select an election:</label>
                            <br>
                            <div class="btn-group-vertical" id="electionSelection">
                                <?php   if ($activeElectionCount > 0) {
                                        for($x = 0; $x < $activeElectionCount; $x++) {
                                            echo "<button type='submit' id='selection'  class='btn btn-warning btn-lg submit' name='selection' value='" . $elections[$x][0] . "'>" . $elections[$x][1] . "</button>";
                                        }
                                    }else{
                                        echo "<p>" . $noElectionString . "</p>";
                                    }
                                ?>
                            </div>
                        </div>       
                	</form>
                </div>
                <!--adding space between form and footer-->
                <div class="form-group">
                    &nbsp;
                </div>
                <div class="form-group">
                    &nbsp;
                </div>
            </div>
        </div>
        <footer class="container-fluid">
            <!--info here: logo, copyright, links, login as admin-->

            <div id="small_logo" class="media">
                <img src="../images/small_logo.png" width="100" height="35" alt="">
            </div>
            <div class="media-body">
            <ul class="list-inline pull right">
                <li><a href="voterHelp.html">Help</a></li>
                <li><a href="../includes/logout.php">Log out</a></li>
                <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
            </ul>
            </div>
        </footer>
    </div>
</body>
</html>
