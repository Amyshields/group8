<?php
  require "../../includes/getConnCandidate.php";
?>
<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <title>Admin Home Page</title>
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
      <p><h2>Add Candidates</h2></p>
      <table>
      <form name="addCandidate" method="POST" action="./addCandidate.php">
        <tr><td>Candidate Name: </td><td><input type="text" name="newName"></td></tr>
        <tr><td>Candidate Party: </td><td><input type="text" name="newParty"></td></tr>
        <tr><td>Candidate Area: </td><td><input type="text" name="newArea"></td></tr>
        <tr><td><button type="submit">Add Candidate</button>
      </form>
    </table>

      <p><h2>View Candidates</h2></p>
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
          $_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
          redirect('../index.php');
      }
      $conn = null;
      ?>
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
