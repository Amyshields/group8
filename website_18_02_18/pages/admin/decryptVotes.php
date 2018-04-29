<?php
require_once('../../includes/functions.php');
include('../../includes/settings.php');

session_start();

if(!isset($_SESSION['admin'])){
    redirect("../../index.php");
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
    $sql_check = "SELECT candidateID FROM GeneralElection2018";
    
    $query = $conn->prepare($sql_check);

    $result = $query->execute();

    $privateKeyPath = '../../RSA/privkey.pem';

    $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));   //Get PRIVATE KEY
    echo $privateKey;
    echo '<br><br>';
    foreach($query as $row){
        $cypherText = $row['candidateID'];
        echo $cypherText;
        echo '<br>';
        openssl_private_decrypt(base64_decode($cypherText), $decrypted, $privateKey);
        echo $decrypted;
        echo '<br>';
        $sql_addToDectrypedTable = "INSERT INTO decryptedVotes (candidateID) VALUES ('$decrypted')";
        $conn->query($sql_addToDectrypedTable);
    }

}


catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
<html>
</html>
