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

$key = $_POST['adminKeyInput'];
$adminUsername = $_SESSION['username'];
$adminID = $_SESSION['adminID'];

if ($local == true){ //Setting up variables for local connection
    global $lservername;
    global $ldbname;
    global $ldbusername;
    global $ldbpassword;
    $servername = $lservername;
    $dbname = $ldbname;
    $dbusername = $ldbusername;
    $dbpassword = $ldbpassword;
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
    $sql_check = "SELECT privateKey FROM adminPrivateKeys WHERE adminUsername =:adminUsername";

    $query = $conn->prepare($sql_check);

    $query->execute(array(':adminUsername' => $adminUsername));

    $num_rows = $query->rowCount();

    // echo $key;

    if ($num_rows > 0) {
        $sql = "UPDATE adminPrivateKeys SET privateKey='$key' WHERE adminUsername='$adminUsername'";
        $conn->query($sql);
        echo "You're existing key has been changed, auto redirecting back in 3 seconds";
    }
    else {
        $sql_addKey = "INSERT INTO adminPrivateKeys (adminID, adminUsername, privateKey) VALUES ('$adminID', '$adminUsername', '$key')";
        $conn->query($sql_addKey);
        echo 'You have added a key for the first time, auto redirecting back in 3 seconds';
    }
}


catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
<html>
<meta http-equiv="refresh" content="3; url=uploadKey.php">
</html>
