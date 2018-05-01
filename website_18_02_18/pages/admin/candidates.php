<?php
  require "../../includes/getConnCandidate.php";
?>
<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
    <body>
      <p>Add Candidates</p>
      <form name="addCandidate" method="POST" action="./addCandidate.php">
        Candidate Name: <input type="text" name="newName"><br>
        Candidate Party: <input type="text" name="newParty"><br>
        Candidate Area: <input type="text" name="newArea"><br>
        <button type="submit">Add Candidate</button>
      </form>

      <p>View Candidates</p>
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
            echo "<p>";
            echo "Candidate Name: <input type='text' value='$candidateName' id='candName$candidateId'>";
            echo "<br>Candidate Party: <input type='text' value='$candidateParty' id='candParty$candidateId'>";
            echo "<br>Candidate Area: <input type='text' value='$candidateArea' id='candArea$candidateId'>";
            echo "<br>";
            echo "<button id='change$candidateId' type='submit' onclick='changeCandidate($candidateId)'>Change Candidate</button>";
            echo "<button id='delete$candidateId' type='submit' onclick='deleteCandidate($candidateId)'>Delete Candidate</button>";
            echo "</p>";
            echo "<div id='candidateError$candidateId'> </div>";

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
