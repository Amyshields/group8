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
$electionName = $_POST['selection'];

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

    // ------------- RSA ---------------
    $sql_clearPrivateKeys = "TRUNCATE adminPrivateKeys";
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

    // Set up private key from file
    $privateKey = openssl_pkey_get_private(file_get_contents($newPrivKeyPath));   //Get PRIVATE KEY
    
    $keyString = openssl_pkey_get_details ( $privateKey );
    // ------------------------------
    
    // Retrieve all candidate IDs to be decrypted
    $sql_check = "SELECT candidateID FROM ".$electionName;
    $query = $conn->prepare($sql_check);
    $result = $query->execute();

    $sql_addDecryptColumn = "ALTER TABLE ".$electionName." ADD decryptedVote varchar(20)";
    $conn->query($sql_addDecryptColumn);

    $sql_removeNIN = "ALTER TABLE ".$electionName." DROP COLUMN voterNIN";
    $conn->query($sql_removeNIN);

    // Decrypt each candidate ID and add to decrypted votes table
    foreach($query as $row){
        $cypherText = $row['candidateID'];
        
        openssl_private_decrypt(base64_decode($cypherText), $decrypted, $privateKey);
        echo "$decrypted";
        $sql_addDecrypted = "UPDATE ".$electionName." SET decryptedVote='$decrypted' WHERE candidateID='$cypherText'";
        $conn->query($sql_addDecrypted);
    }
    

     // Display if key is valid and redirect
     if ($keyString['key'] == null){
        echo "Incorrect private key parts.";
        echo '<meta http-equiv="refresh" content="3;url=index.php">';
    } else {
        $sql_removeEncrypted = "ALTER TABLE ".$electionName." DROP COLUMN candidateID";
        $conn->query($sql_removeEncrypted);
        $sql_changeIsEncrypted = "UPDATE election SET isEncrypted=0 WHERE electionName='$electionName'";
        $conn->query($sql_changeIsEncrypted);
        echo "Votes decrypted successfully.";
        echo '<meta http-equiv="refresh" content="3;url=index.php">';
        // Delete private keys from database
        //$sql_clearPrivateKeys = "TRUNCATE adminPrivateKeys";
        //$conn->query($sql_clearPrivateKeys);
    }

    // Delete key from server
    unlink($newPrivKeyPath);
}


catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
<html>
</html>
