<?php
  require "../../includes/getConnCandidate.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>View and Add Elections</title>
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
    <div class="container-fluid col-sm-offset-2">
      <div class="wrap">
      <h1>Add Candidates</h1>
      <!--Error box here?-->
      <table>
        <form name="addCandidate" method="POST" action="./addCandidate.php">
        <tr><td>Candidate Name: </td><td><input type="text" name="newName"></td></tr>
        <tr><td>Candidate Party: </td><td><input type="text" name="newParty"></td></tr>
        <tr><td>Candidate Area: </td><td><input type="text" name="newArea"></td></tr>
        <tr><td><button type="submit">Add Candidate</button></td></tr>
        </form>
      </table>
    <h2>View Candidates</h2>
    <?php

      $sql_select = "SELECT * FROM candidate";
      $query = $conn->query($sql_select);

      $query->execute();

      $num_rows = $query->rowCount();

      if ($num_rows > 0){

          foreach ($query as $row) {
            $candidateId = $row['candidateID'];
            $candidateName = $row['candidateName'];
            $candidateParty = $row['candidateParty'];
            $candidateArea = $row['candidateArea'];
            echo "<table>";
            echo "<tr><td>Candidate Name: </td><td><input type='text' value='$candidateName' id='candName$candidateId'></td></tr>";
            echo "<tr><td>Candidate Party: </td><td><input type='text' value='$candidateParty' id='candParty$candidateId'></td></tr>";
            echo "<tr><td>Candidate Area: </td><td><input type='text' value='$candidateArea' id='candArea$candidateId'></td></tr>";
            echo "<tr>";
            echo "<td><button id='change$candidateId' type='submit' onclick='changeCandidate($candidateId)'>Change Candidate</button></td>";
            echo "<td><button id='delete$candidateId' type='submit' onclick='deleteCandidate($candidateId)'>Delete Candidate</button></td>";
            echo "</tr><tr><td><div id='candidateError$candidateId'> </div></td></tr></table><br>";

          }
      }
      else{
          $_SESSION['error'] =  "<div class='alert alert-danger'><strong>Error!</strong> Couldn't connect to the database</div>";
        #$_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
          redirect('../index.php');
      }
      $conn = null;
    ?>
    </div>
    </div>
    <footer class="container-fluid">
      <!--info here: logo, copyright, links, login as admin-->

      <div id="small_logo" class="media">
        <img src="../../images/small_logo.png" width="100" height="35" alt="">
      </div>
      <div class="media-body">
      <ul class="list-inline">
        <li><a href="adminHelp.html">Help</a></li>
        <li><a href="..\admin\index.php">Back</a></li>
        <li><a href="..\..\includes\logout.php">Log out</a></li>
        <li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
      </ul>
      </div>
    </footer>
  </div>
  </body>
<script>
  function changeCandidate(id){
    var inputName = document.getElementById("candName" + id).value;
    var inputParty = document.getElementById("candParty" + id).value;
    var inputArea = document.getElementById("candArea" + id).value;

    $.post("./changeCandidate.php", { //post values to php
      id: id,
      area: inputArea,
      name: inputName,
      party: inputParty,
    }, function(result) {
      if (result == "") { //check if result is empty, if not there is an error
      } else {
        document.getElementById("candidateError" + id).innerHTML = result; //display error
      }
    });
  }

  function deleteCandidate(id){
    $.post("./deleteCandidate.php", { //post id to php
        id: id
      }, function(result) {
        location.reload(); //refresh page
      });
  }
</script>
 </html>
