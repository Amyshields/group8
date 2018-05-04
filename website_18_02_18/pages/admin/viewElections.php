<?php
  include('../../includes/settings.php');
  session_start();

  if (!isset($_SESSION['admin'])){
	   redirect("../index.php");
  }

  if(!isset($_SESSION['logged_in'])){
     $_SESSION['error'] =  "<div class='alert alert-danger alert-dismissible'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            <strong>Error!</strong> Please enter your National Insurance Number and Password</div>";
        #$_SESSION['error'] = "Please enter your National Insurance Number and Password";
     header("Location: ../../includes/login-admin.php");
  }

  global $local; //Setting up database based on local variable
  $servername = ""; //Set up connection variables
  $dbname = "";
  $dbusername = "";
  $dbpassword = "";
  $table = "Election";
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
      $table = "election"; //Fix for wamp server importing tables names as all lowercase
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
  }
  catch(PDOException $e){
    #################here
      echo $sql . "<br>" . $e->getMessage();
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
			<meta charset="utf-8">
			<title>View Elections</title>
			<!--For Bootstrap, to make page responsive on mobile-->
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<!--For Bootstrap, to load the css information from a CDN-->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<link href="../../css/electago.css" rel="stylesheet" type="text/css">
			<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
		  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    <header class="container-fluid text-center">
      <div id="logo">
        <img src="../../images/logo.png" width="300" height="100" alt="">
      </div>
    </header>
    <div class="container">
    <h1>View and Delete Current Elections</h1>
    <br>
    <div class="container-fluid col-sm-offset-2">
      <div class="wrap">
        <div class="col-sm-5">
        <p>View all the current elections below. Click delete to delete the election or results to be taken to the results and demographics page.</p>
        </div>
        <div class="col-sm-3">
          <a href="../admin/index.php" class="btn btn-lg btn-primary pull-right"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go Back</a>
        </div>
      <?php
        $sql_select = "
        SELECT * FROM election

        ORDER BY electionArea
        ";
        $query = $conn->query($sql_select);
        $num_rows = $query->rowCount();
        $electionCandidates = [];
        $electionData = $query->fetchAll();

        if ($num_rows > 0){
          foreach ($electionData as $row) {
            $electionId = $row['electionID'];
            $electionName = $row['electionName'];
            $electionType = $row['electionType'];
            $electionArea = $row['electionArea'];
            $electionDate = $row['electionDate'];
            $electionDisplayName = $row['electionDisplayName'];
            $electionCandidates = explode(";", $row['electionCandidates']);
            $electionIsEncrypted = $row['isEncrypted'];

            //<div class="panel panel-default">
            //   <!-- Default panel contents -->
            //   <div class="panel-heading">Panel heading</div>
            //   <div class="panel-body">
            //     <p>...</p>
            //   </div>

            //   <!-- Table -->
            //   <table class="table">
            //     ...
            //   </table>
            // </div>

            echo "<div class='panel panel-default col-sm-9 electionList'>
                  <div class='panel-heading info'>
                    <h2 class='text-center'>$electionDisplayName</h2>
                    <table class='table'>
                    <tr><td>Election Name: </td><td>$electionName</td></tr>
                    <tr><td>Display Name: </td><td>$electionDisplayName</td></tr>
                    <tr><td>Type: </td><td>$electionType</td></tr>
                    <tr><td>Area: </td><td>$electionArea</td></tr>
                    <tr><td>Date: </td><td>$electionDate</td></tr>";
            if ($electionIsEncrypted==1){
              echo "<tr><td>Encrypted: </td><td>YES</td></tr></table></div>";
            } else {
              echo "<tr><td>Encrypted: </td><td>NO</td></tr></table></div>";
            }


            if ($electionType != "REF"){
              echo "<div class='panel-body'> <h3>Election Candidates:</h3> <table class='table'>
                    <thead> <th>Name:</th> <th>Party:</th> <th>Area:</th> </thead>";
              $sql_select2 = "";
              foreach ($electionCandidates as $currentCandidate){

                $sql_select2 = "
                SELECT `candidateName`, `candidateParty`, `candidateArea`
                FROM candidate
                WHERE candidateID = $currentCandidate;
                ";

                $queryCand = $conn->query($sql_select2);
                $num_Cand = $queryCand->rowCount();

                if ($num_Cand = 0){
                  echo "<tr><td>This candidate has been removed from the system</td></tr>";
                } else {
                    $candidateInfo = $queryCand->fetchAll();
                    $candName = $candidateInfo[0]['candidateName'];
                    $candParty = $candidateInfo[0]['candidateParty'];
                    $candArea = $candidateInfo[0]['candidateArea'];
                    echo "<tr><td> $candName </td><td> $candParty </td><td> $candArea</td></tr>";

                }
              }
              echo "</td></tr></table>";
            }
            else{
              echo "<div class='panel-body'>";

            }
            echo "<a href='demographics.php' class='btn btn-success col-sm-5 pull-left'  type='submit'><span class='glyphicon glyphicon-check'></span> Results</a>
              &nbsp;
              <button  class='btn btn-danger col-sm-5 pull-right' id='delete$electionId' type='submit' onclick='deleteElection($electionId)'><span class='glyphicon glyphicon-remove'></span> Delete Election</button><br></div><br></div>";
          }
        } else {
            $_SESSION['error'] = "<div class='alert alert-danger'>
                        <strong> Error!</strong>Couldn't fetch results, check debug section of settings.php</div>";
            //redirect('../index.php');
        }
        $conn = null;
      ?>
      <!--a href="../admin/index.php" class="btn btn-lg btn-primary center-block"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp; Go Back</a-->
      </div>
    </div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="../../images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="..\..\adminHelp.html">Help</a></li>
        <li><a href="..\admin\index.php">Back</a></li>
        <li><a href="..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
  </body>

<script>
  function deleteElection(id){

    if (confirm("Are you sure you want to delete this election?")) {
      $.post("./deleteElection.php", { //post id to php
          id: id
        }, function(result) {
          location.reload(); //refresh page
        });
    } else {
      txt = "You pressed Cancel!";
    }
  }
</script>
 </html>
