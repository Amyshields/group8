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

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_select = "SELECT * FROM ".$table." WHERE candidateArea=:userConstituency";
    $query = $conn->prepare($sql_select);

    $query->execute(array(':userConstituency' => $userConstituency));

    $num_rows = $query->rowCount();

    if ($num_rows > 0){
        $candidates = array();

        foreach ($query as $row) {
            $candidateID = $row['candidateID'];
            $candidateName = $row['candidateName'];
            $candidateParty = $row['candidateParty'];

            $thisCandidate = array($candidateID, $candidateName, $candidateParty);
            array_push($candidates, $thisCandidate);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Voting Page</title>
	<!--For Bootstrap, to make page responsive on mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--For Bootstrap, to load the css information from a CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container-fluid">    
	<header class="container-fluid text-center">
	<h1>Electago - Voting Page</h1>
		<!--header image here-->
		<!---->
	</header>
<!--container class used in bootstrap to make a dynamic container of a fixed size-->
<div class="container">
	<form action="voted.php" method="post">
		<h2>Local Election</h2>
		<p> Please select who you wish to vote for, for your constituency <?php echo$userConstituency;?>:</p>
        <?php for ($x = 0; $x < $num_rows; $x++) {
            echo"<input type='radio' id='radio' name='radio' value='" . $candidates[$x][0] . "'> <label for='Choice".$x."'>" . $candidates[$x][1] . "</label><p><p>";
        }?>
        </select>
		<input type="submit" class="btn btn-default" value="Vote" autofocus>
	</form>
</div>
        <?php
        if(isset($_POST['submit'])){
            if(isset($_POST['radio'])){
                echo"wow";
                echo$_POST['radio'];
                $selectedCandidateID = $_POST['radio'];
                $userNIN = $_POST['username'];
                mysqli_query("INSERT INTO 'GeneralElection2018'(voterNIN, candidateID)
                VALUES('$userNIN', '$selectedCandidateID')");
            }
        }?>

<div class="container">
	<form action="#.php">
		<h2>National Election</h2>
		<p> Brexit, yes or no? </p>
			<input type="radio" id="Choice1" name="choice1" ><label for="choice1">  Yes </label><p><p>
			<input type="radio" id="Choice1" name="choice1"><label for="choice2">  No </label><p>
			<input type="submit" class="btn btn-default" value="Vote" autofocus>
	</form>
	
	<a href="../includes/logout.php"><p>Log out</p></a>
</div>

<footer class="container-fluid text-left">
	<!--info here: logo, copyright, links, login as admin-->
	<ul>
		<li><a href="#">Help</a></li>
		<li><p>Other links</p></li>
		<li><a href="#">Login as admin</a></li>
		<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
	</ul>
</footer>

</html>
<script>
    function submitVote(){

    }
</script>