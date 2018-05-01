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


if ($_SESSION['hasVoted'] == 1) {
    $stringToEcho = "<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                    <div class='container'> <p>You have already voted. However, feel free to change your vote:</p> </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><p>Other links</p></li>
                        <li><a href='pages/index.php'>Back to voter login</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                </footer>
                </body>
                </html> ";
} else {
    $stringToEcho = "<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                    <div class='container'> <p>You have not voted yet, select an option from below: </p> </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><p>Other links</p></li>
                        <li><a href='pages/index.php'>Back to voter login</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                </footer>
                </body>
                </html>";
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

		$sql_select = "SELECT ";

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
    echo  "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='utf-8'>
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                    <div class='container'> <p>". $sql . "<br>" . $e->getMessage() . "</p> </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><p>Other links</p></li>
                        <li><a href='pages/index.php'>Back to voter login</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                </footer>
                </body>
                </html>";
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
    <link href="css/electago.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container-fluid">
	<header class="container-fluid text-center">
        <div id="logo">
            <img src="images/logo.png" width="300" height="100" alt="">
        </div>
	</header>
<!--container class used in bootstrap to make a dynamic container of a fixed size-->
    <h1>Electago - Voting Page</h1>
    <div class="container">
    	<form action="voted.php" method="post">
    		<h2>General Election 2018</h2>
    		<p> Constituency: <b><?php echo$userConstituency;?></b>.</p>
            <p> <?php echo $stringToEcho ?> </p>
            <?php for ($x = 0; $x < $num_rows; $x++) {
                echo"<input type='radio' id='radio' name='radio' value='" . $candidates[$x][0] . "'> <label for='Choice".$x."'>" . $candidates[$x][1] ." - ". $candidates[$x][2] . "</label><p><p>";
            }?>
            <input type='radio' id='radio' name='radio' value='0'> <label for='ChoiceSpoil'>Spoil Ballot</label><p><p>
            </select>
    		<input type="submit" class="btn btn-default" value="Vote" autofocus>
    	</form>
    </div>
    <footer class="container-fluid">
    	<!--info here: logo, copyright, links, login as admin-->
        <div id="small_logo" class="media">
            <img src="images/small_logo.png" width="100" height="35" alt="">
        </div>
        <div class="media-body">
        	<ul>
                <li><a href="../includes/logout.php"><p>Log out</p></a></li>
        		<li><a href="#">Help</a></li>
        		<li><p>Other links</p></li>
        		<li><a href="#">Login as admin</a></li>
        		<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
        	</ul>
        </div>
    </footer>
</html>
