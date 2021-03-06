<?php
/*Cyrus Dobbs C1529854*/
require_once('../includes/functions.php');
include('../includes/settings.php');

session_start();

if (isset($_SESSION['admin'])){
    redirect("../index.php");
}

if(!isset($_SESSION['logged_in'])){
    $_SESSION['error'] = "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Please enter your National Insurance Number and Password
                        </div>";
    header("Location: ../index.php");
}

global $local; //Setting up database based on local variable
$servername = ""; //Set up connection variables
$dbname = "";
$dbusername = "";
$dbpassword = "";
$table = "candidate";


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

    $userConstituency = $_SESSION['constituency'];
    $userNIN = $_SESSION['username'];
    $selectedVote = $_POST['radio'];
    $electionName = $_POST['electionName'];
    $electionType = $_POST['electionType'];

    if ($selectedVote == ""){
        redirect("dashboard.php?noSelection=y");
    }

    // echo $selectedVote;
    // echo $electionName;
    // echo $userNIN;

    // ------------ RSA --------------
    $publicKeyPath = '../RSA/pubkey.pem';

    $pkeyid = openssl_pkey_get_public(file_get_contents($publicKeyPath));
    $details = openssl_pkey_get_details($pkeyid);
    $public_key_from_pem = ($details['key']);    //Get PUBLIC KEY
    // echo $public_key_from_pem;

    // echo $selectedVote;
    openssl_public_encrypt($selectedVote, $output, $public_key_from_pem); // Encrypt
    $encString = base64_encode($output); // Encode

    // echo $encString;
    // --------------------------------

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql_check = "SELECT voterNIN FROM ".$electionName." WHERE voterNIN ='$userNIN'";
    
    $query = $conn->prepare($sql_check);

    $query->execute();

    $num_rows = $query->rowCount();

    if ($num_rows > 0) {
        $sql = "UPDATE ".$electionName." SET candidateID='$encString' WHERE voterNIN='$userNIN'";
        $conn->query($sql);
        #page that shows if youve changed your vote
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='utf-8'>
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='../css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='../images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                    <div class='alert alert-success alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong>Vote Changed!</strong> Your existing vote has been changed, auto redirecting back in 3 seconds.
                    </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='../images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><a href='../dashboard.php'>Back to dashboard</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                    <meta http-equiv='refresh' content='3;url=dashboard.php?voted=y'>
                </footer>
                </body>
                </html>";
        #echo "Your existing vote has been changed, auto redirecting back in 3 seconds";-->
    }
    else {
        $sql = "INSERT INTO ".$electionName." (voterNIN, candidateID)
                VALUES ('$userNIN', '$encString')";
        $conn->query($sql);
        #page that shows if your'e voting for the first time
        #echo 'You have voted for the first time, auto redirecting back in 3 seconds';
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='utf-8'>
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='../css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='../images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--container class used in bootstrap to make a dynamic container of a fixed size-->
                    <div class='alert alert-success alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong>Success!</strong> You have voted for the first time, auto redirecting back in 3 seconds
                    </div>
                <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='voterHelp.html'>Help</a></li>
                        <li><a href='voterHelp.html/#privacy'>Privacy</a></li>
                        <li><a href='../dashboard.php'>Back to dashboard</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                    <meta http-equiv='refresh' content='3;url=dashboard.php?voted=y'>
                </footer>
                </body>
                </html>";
    }

}
catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
?>
