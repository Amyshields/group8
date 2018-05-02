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
        echo "<div class='container'> 
                            <span class='glyphicon glyphicon-exclamation-sign'></span>
                            <p>Your existing vote has been changed, auto redirecting back in 3 seconds</p> 
                        </div>";
        #echo "Your existing vote has been changed, auto redirecting back in 3 seconds";-->
    }
    else {
        $sql = "INSERT INTO ".$electionName." (voterNIN, candidateID)
                VALUES ('$userNIN', '$encString')";
        $conn->query($sql);
        #page that shows if your'e voting for the first time
        #echo 'You have voted for the first time, auto redirecting back in 3 seconds';
        echo "<div class='container'> <p>You have voted for the first time, auto redirecting back in 3 seconds</p> </div>";
    }
}
catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
?>
