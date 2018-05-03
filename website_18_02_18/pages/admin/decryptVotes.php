<?php
/*Cyrus Dobbs C1529854*/
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
        $_SESSION['error'] =  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Couldn't connect to the database</div>";
        #$_SESSION['error'] = "Couldn't connect to the database";
        redirect('../index.php');
    }


    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ---------- Time check -----------
    $sql_getTime = "SELECT electionDate FROM election WHERE electionName ='$electionName'";
    $timeQuery = $conn->prepare($sql_getTime);
    $timeQuery->execute();

    date_default_timezone_set('Europe/London');
    $today = date("Y-m-d H:i:s");
    
    foreach ($timeQuery as $row){

        $date = $row['electionDate'];
        $electDate = $date . " 00:00:00";

        if ($electDate > $today){
            redirect("decryptScreen.php?tooEarly=y");
        }
    }
    // ---------------------------------
    
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

    // Set up private key from file
    $privateKey = openssl_pkey_get_private(file_get_contents($newPrivKeyPath));   //Get PRIVATE KEY
    
    $keyString = openssl_pkey_get_details ( $privateKey );
    // ------------------------------
    
    // Retrieve all candidate IDs to be decrypted
    $sql_check = "SELECT * FROM ".$electionName;
    $query = $conn->prepare($sql_check);
    $result = $query->execute();

    // Decrypt each candidate ID and add to decrypted votes table
    foreach($query as $row){
        $cypherText = $row['candidateID'];
        $voterNIN = $row['voterNIN'];
        openssl_private_decrypt(base64_decode($cypherText), $decrypted, $privateKey);
        $sql_addDecrypted = "UPDATE ".$electionName." SET candidateID='$decrypted' WHERE voterNIN='$voterNIN'";
        $conn->query($sql_addDecrypted);
    }
    

     // Display if key is valid and redirect
     if ($keyString['key'] == null){
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='utf-8'>
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='../../css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='../../images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--Error message here-->
                    <div class='container'> <p>Incorrect private key parts.</p> </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='../../images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><a href='pages/admin/index.php'>Back to dashboard</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                </footer>
                </body>
                </html>";
            echo '<meta http-equiv="refresh" content="3;url=index.php">';
    } else {
        // Remove national insurance numbers from table
        $sql_removeNIN = "ALTER TABLE ".$electionName." DROP COLUMN voterNIN";
        $conn->query($sql_removeNIN);

        // Change inEncrypted
        $sql_changeIsEncrypted = "UPDATE election SET isEncrypted=0 WHERE electionName='$electionName'";
        $conn->query($sql_changeIsEncrypted);

        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='utf-8'>
                    <title>Home Page</title>
                    <!--For Bootstrap, to make page responsive on mobile-->
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <!--For Bootstrap, to load the css information from a CDN-->
                    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
                    <link href='../../css/electago.css' rel='stylesheet' type='text/css'>
                    <link href='https://fonts.googleapis.com/css?family=Montserrat|Open+Sans' rel='stylesheet'>
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
                    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                </head>
                <body>
                    <header class='container-fluid text-center'>
                        <div id='logo'>
                            <img src='../../images/logo.png' width='300' height='100' alt=''>
                        </div>
                    </header>
                    <!--Error message here-->
                    <div class='container'> <p>Votes decrypted successfully.</p> </div>
                    <footer class='container-fluid'>
                    <!--info here: logo, copyright, links, login as admin-->

                    <div id='small_logo' class='media'>
                        <img src='../images/small_logo.png' width='100' height='35' alt=''>
                    </div>
                    <div class='media-body'>
                    <ul class='list-inline pull right'>
                        <li><a href='#'>Help</a></li>
                        <li><a href='pages/admin/index.php'>Back to dashboard</a></li>
                        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
                    </ul>
                    </div>
                </footer>
                </body>
                </html>";
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