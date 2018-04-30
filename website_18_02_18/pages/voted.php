<?php
require_once('../includes/functions.php');
include('../includes/settings.php');

session_start();

if (isset($_SESSION['admin'])){
    redirect("../index.php");


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

    $publicKeyPath = '../RSA/pubkey.pem';

    $pkeyid = openssl_pkey_get_public(file_get_contents($publicKeyPath));
    $details = openssl_pkey_get_details($pkeyid);
    $public_key_from_pem = ($details['key']);    //Get PUBLIC KEY
    // echo $public_key_from_pem;

    openssl_public_encrypt($selectedCandidateID, $output, $public_key_from_pem); // Encrypt
    $encString = base64_encode($output); // Encode
    // echo $encString;

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_check = "SELECT voterNIN FROM GeneralElection2018
                WHERE voterNIN =:userNIN";
                
    $query = $conn->prepare($sql_check);

    $query->execute(array(':userNIN' => $userNIN));

    $num_rows = $query->rowCount();

    if ($num_rows > 0) {
        $sql = "UPDATE GeneralElection2018 SET candidateID='$encString' WHERE voterNIN='$userNIN'";
        $conn->query($sql);
<<<<<<< HEAD
        echo "<!DOCTYPE html>
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
                    <div class='container'> <p>Your existing vote has been changed, auto redirecting back in 3 seconds</p> </div>
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
=======
        echo "Your existing vote has been changed, auto redirecting back in 3 seconds";
>>>>>>> front-end-changes
    }
    else {
        $sql = "INSERT INTO GeneralElection2018 (voterNIN, candidateID)
                VALUES('$userNIN', '$encString')";
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
mysql_close();
?>
<html>
<meta http-equiv="refresh" content="3; url=voting.php">
</html>
