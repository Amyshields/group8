<?php
require_once('../includes/functions.php');
include('../includes/settings.php');

session_start();

if (isset($_SESSION['admin'])){
    redirect("../index.php");
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
$table = "Candidate";
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
        $_SESSION['error'] = "Couldn't connect to the database";
        redirect('../index.php');
    }

    $userNIN = $_SESSION['username'];
    $selectedCandidateID = $_POST['radio'];

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_check = "SELECT voterNIN FROM GeneralElection2018
                WHERE voterNIN =:userNIN";
    $query = $conn->prepare($sql_check);

    $query->execute(array(':userNIN' => $userNIN));

    $num_rows = $query->rowCount();

    if ($num_rows > 0) {
        $sql = "UPDATE GeneralElection2018 SET candidateID='$selectedCandidateID' WHERE voterNIN='$userNIN'";
        $conn->query($sql);
        echo "You're existing vote has been changed, auto redirecting back in 3 seconds";
    }
    else {
        $sql = "INSERT INTO GeneralElection2018 (voterNIN, candidateID)
                VALUES('$userNIN', '$selectedCandidateID')";
        $conn->query($sql);
        echo 'You have voted for the first time, auto redirecting back in 3 seconds';
    }

    // Change hasVoted
    $sql_updateNew = "UPDATE Voter SET hasVoted=1 WHERE username='$userNIN'";
    $conn->query($sql_updateNew);
    $_SESSION['hasVoted'] = 1;
}


catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}


$conn = null;
?>
<html>
<meta http-equiv="refresh" content="3; url=voting.php">
</html>