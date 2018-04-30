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

    // Retrieve admin keys
    $sql_getKeyParts = "SELECT privateKey FROM adminPrivateKeys ORDER BY adminID";

    $keys = $conn->prepare($sql_getKeyParts);
    $keys->execute();

    // Initialise string
    $privateKeyString = '';
    // Join all admin keys together
    foreach($keys as $row){
        $privateKeyString .= $row['privateKey'];
    }

    // Split start & end from middle 
    $keyStart = substr($privateKeyString, 0, 27);
    $keyMiddle = substr($privateKeyString, 27, -25);
    $keyEnd = substr($privateKeyString, -25, 25);

    // Replace whitespace with new lines
    $keyMiddle = preg_replace('/\s+/', "\r\n", $keyMiddle);

    // Rejoin
    $keyFull = $keyStart; $keyFull .= $keyMiddle; $keyFull .= $keyEnd;

    // Private key path
    $newPrivKeyPath = 'newPrivKey.pem';

    // Write string to pem file
    file_put_contents($newPrivKeyPath , $keyFull);

    // echo $keyFull;
    // echo '<br>';
    // echo '<br>';

    // Retrieve all candidate IDs to be decrypted
    $sql_check = "SELECT candidateID FROM GeneralElection2018";
    $query = $conn->prepare($sql_check);
    $result = $query->execute();

    // Set up private key from file
    $privateKey = openssl_pkey_get_private(file_get_contents($newPrivKeyPath));   //Get PRIVATE KEY
    

    $keyString = openssl_pkey_get_details ( $privateKey );
    // echo $keyString['key'];
    
    echo '<br><br>';
    // Decrypt each candidate ID and add to decrypted votes table
    foreach($query as $row){
        $cypherText = $row['candidateID'];
        // echo $cypherText;
        // echo '<br>';
        openssl_private_decrypt(base64_decode($cypherText), $decrypted, $privateKey);
        // echo $decrypted;
        // echo '<br>';
        $sql_addToDectrypedTable = "INSERT INTO decryptedVotes (candidateID) VALUES ('$decrypted')";
        $conn->query($sql_addToDectrypedTable);
    }

     // Display if key is valid and redirect
     if ($keyString['key'] == null){
        echo "Incorrect private key parts.";
        echo '<meta http-equiv="refresh" content="3;url=index.php">';
    } else {
        echo "Votes decrypted successfully.";
        echo '<meta http-equiv="refresh" content="3;url=index.php">';
        // Delete private keys from database
        $sql_clearPrivateKeys = "TRUNCATE adminPrivateKeys";
        $conn->query($sql_clearPrivateKeys);
    }

    // Delete key from server
    unlink($newPrivKeyPath);
}


catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
mysql_close();
?>
<html>
</html>
