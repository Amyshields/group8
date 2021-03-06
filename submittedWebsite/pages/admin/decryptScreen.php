<?php
/*Cyrus Dobbs C1529854*/
require_once('../../includes/functions.php');
include('../../includes/settings.php');

session_start();

if(!isset($_SESSION['admin'])){
	redirect("../../index.php");
}

if(!isset($_SESSION['logged_in'])){
   $_SESSION['error'] = "Please enter your National Insurance Number and Password";
   header("Location: ../index.php");
}

global $local; //Setting up database based on local variable
$servername = ""; //Set up connection variables
$dbname = "";
$dbusername = "";
$dbpassword = "";

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
        $_SESSION['error'] =  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Couldn't connect to the database</div>";
        #$_SESSION['error'] = "Couldn't connect to the database";
        redirect('../index.php');
    }

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_selectElections = "SELECT * FROM election WHERE isEncrypted=1";

    $query = $conn->prepare($sql_selectElections);

    $query->execute();

    $num_rows = $query->rowCount();

    if ($num_rows > 0) {

        $elections = array();
        
        foreach ($query as $row) {
            
            $electionDate = $row['electionDate'];
            
            // $electionID = $row['electionID'];
            $electionDisplayName = $row['electionDisplayName'];
            $electionName = $row['electionName'];
            // $electionType = $row['electionType'];
            // $electionArea = $row['electionArea'];
            // $electionDate = $row['electionDate'];

            $thisElection = array($electionName, $electionDisplayName);
            array_push($elections, $thisElection);
        }
    }
    else{
        $noElectionString = "There are no elections currently.";
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
	<title>Decrypt Selection</title>
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
    <div class="container">
    <div class="container-fluid col-sm-offset-2">
        <h1 class="pull-left">Decrypt Election</h1>
        <div class="wrap">
        <!--Error box here?-->
        <div class="col-sm-10" id="intro">
            <p>Select an election from the dropdown and click "Decrypt".</p> 
        </div> 
<!--container class used in bootstrap to make a dynamic container of a fixed size-->
        <div class="col-sm-8">
        	<form action="decryptVotes.php" method="post">

                <div class="input-group">
                    <select class="custom-select form-control input-lg" name="selection">
                        <?php   if ($num_rows > 0) {
                                    for($x = 0; $x < $num_rows; $x++) {
                                        echo "<option id='selection' name='selection' value='" . $elections[$x][0] . "'>" . $elections[$x][1] . "</option>";
                                    }
                                }else{
                                    echo "<p>" . $noElectionString . "</p>";
                                }
                            ?>
                    </select>
                    <div class="input-group-btn">
                        <!--input type="submit" class="btn btn-lg btn-default" value="Decrypt" autofocus-->
                        <button class="btn btn-lg btn-warning" type="submit">Decrypt</button>
                        <br>
                    </div>
                </div>        
        	</form>
            <br>
            <p>An election can not be decrypted until after voting has closed and all the admin keys have been uploaded.</p>
            <br>
            <?php   
                if(isset($_GET['tooEarly'])){
                    echo "<div class='alert alert-warning alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong> Can not decypt!</strong> That election has not ended yet.</div>"; 
                }
            ?>
            <hr>
            <p>Or go back to the admin dashboard.</p>
            <a href="../admin/index.php" class="btn btn-lg btn-primary center-block"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go Back</a>
            <br>
        </div>
    </div>
</div>
    <footer class="container-fluid">
        <!--info here: logo, copyright, links, login as admin-->

        <div id="small_logo" class="media">
            <img src="../../images/small_logo.png" width="100" height="35" alt="">
        </div>
        <div class="media-body">
        <ul class="list-inline pull right">
            <li><a href="adminHelp.html">Help</a></li>
            <li><a href="../../includes/logout.php">Log out</a></li>
            <li><a href="index.php">Back</a></li>
            <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
        </ul>
        </div>
    </footer>
</div>
</body>
</html>
