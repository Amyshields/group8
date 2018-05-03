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
        $_SESSION['error'] =  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Couldn't connect to the database</div>";
        #$_SESSION['error'] = "Couldn't connect to the database";
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
        /*echo "<!DOCTYPE html>
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
                        <strong>Key Changed!</strong> Your existing key has been changed, auto redirecting back in 3 seconds.
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
                </html>";*/
        echo "<div class='alert alert-success'>
                        <strong>Key Changed!</strong> Your existing key has been changed, auto redirecting back in 3 seconds.
                    </div>>"
        #echo "Your existing key has been changed, auto redirecting back in 3 seconds";
    }
    else {
        $sql_addKey = "INSERT INTO adminPrivateKeys (adminID, adminUsername, privateKey) VALUES ('$adminID', '$adminUsername', '$key')";
        $conn->query($sql_addKey);
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
                        <strong>Key Added!</strong> You have added a key for the first time, auto redirecting back in 3 seconds.
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
        #echo 'You have added a key for the first time, auto redirecting back in 3 seconds';
    }
}


catch(PDOException $e){
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
                    <div class='alert alert-danger alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong>Error!</strong>". $sql . "<br>" . $e->getMessage();."
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
        #echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
<html>
<meta http-equiv="refresh" content="3; url=uploadKey.php">
</html>
