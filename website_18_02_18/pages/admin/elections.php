<?php
  include('../../includes/settings.php');
  session_start();

  if (!isset($_SESSION['admin'])){
	   redirect("../index.php");
  }

  if(!isset($_SESSION['logged_in'])){
     $_SESSION['error'] = "Please enter your National Insurance Number and Password";
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
          $_SESSION['error'] = "Couldn't connect to the database";
          redirect('../index.php');
      }

      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e){
      echo $sql . "<br>" . $e->getMessage();
  }

?>
<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
    <body>
      <p>Create Election</p>
      <div id="addElection">
        Election Name: <input type="text" name="newName" id="newName"><br>
        <!-- Cy Added -->
        Election Display Name: <input type="text" name="newDisplayName" id="newDisplayName"><br>
        Election Type:
        <select name="newType" id="newType">
          <option value="FPTP">FPTP</option>
          <option value="STV">STV</option>
          <option value="REF">Referendum</option>
        </select>
        <br>
        Election Area: <input type="text" name="newArea" id="newArea"><br>
        Election Date: <input type="date" name="newDate" id="newDate"><br>
        <div id="addCands"></div>
        <button type="button" id="addCandidateBtn" onclick="addCandidate()">Add Candidate</button>
        <button type="button" id="newCandidateBtn" onclick="newCandidate()">+</button>
        <button type="submit" onclick="createElection()">Create Election</button>
        <button type="button" onclick="location.reload()">Clear</button>
        <div id="createElectionErrorDisplay"></div>
      </div>

      <p>View Election Settings</p>
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
            $electionDisplayName = $row['electionDisplayName']; // Cy Added
            $electionCandidates = explode(";", $row['electionCandidates']);
            $electionIsEncrypted = $row['isEncrypted']; // Cy Added

            echo "<p>";
            echo "Election Name: $electionName";
            echo "<br>Election DisplayName: $electionDisplayName"; // Cy Added
            echo "<br>Election Type: $electionType";
            echo "<br>Election Area: $electionArea";
            echo "<br>Election Date: $electionDate";
            if ($electionIsEncrypted==1){ // Cy Added
              echo "<br>Encrypted: YES";
            } else {
              echo "<br>Encrypted: NO";
            }


            if ($electionType != "REF"){
              echo "<br>Election Candidates: ";
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
                  echo "This candidate has been removed from the system";
                } else {
                    $candidateInfo = $queryCand->fetchAll();
                    $candName = $candidateInfo[0]['candidateName'];
                    $candParty = $candidateInfo[0]['candidateParty'];
                    $candArea = $candidateInfo[0]['candidateArea'];
                    echo "<br>Name: $candName Party: $candParty Area: $candArea";

                }
              }
            }
            echo "<br><button id='delete$electionId' type='submit' onclick='deleteElection($electionId)'>Delete Election</button>";
          }
        } else {
            $_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
            //redirect('../index.php');
        }
        $conn = null;
      ?>
  </body>

<script>
  var info = [];
  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("newCandidateBtn").style.display = "none";
  });

  function addCandidate(){
    var area = document.getElementById("newArea").value;
    if (area == ""){
      document.getElementById("createElectionErrorDisplay").innerHTML = "Please enter an area for the election";
    } else {
      $.post("./getCandidates.php", { //post id to php
          area: area
        }, function(result) {
          if (result == "Error"){
            document.getElementById("createElectionErrorDisplay").innerHTML = "This area has no candidates.";
          } else {
            info = JSON.parse(result);
            newCandidate();
            document.getElementById("addCandidateBtn").style.display = "none";
            document.getElementById("newCandidateBtn").style.display = "block";
            document.getElementById("newArea").readonly = true;
          }
        });
      }
  }
  function newCandidate(){
    var displayHTML = "<div><select class='candidates'>";
    var splitInfo = [];
    var newDiv = document.createElement("div");
    for (var i = 0; i < info.length; i++) {
      splitInfo = info[i].split(";");
      displayHTML += "<option value='" + splitInfo[0] + "'>" + splitInfo[1] + ", " + splitInfo[2] + ", " + splitInfo[3] + "</option>";
    }
    displayHTML += "</select></div>"
    newDiv.innerHTML = displayHTML;
    document.getElementById("addCands").appendChild(newDiv);
  }
  function createElection(){

    var name = document.getElementById("newName").value;
    var displayName = document.getElementById("newDisplayName").value; // Cy Added
    var type = $("#newType").val();
    var area = document.getElementById("newArea").value;
    var date = document.getElementById("newDate").value;

    var candidates = "";

    var candidateInputs = [];
    candidateInputs = document.getElementsByClassName("candidates");
    var candidateInputsArr = [];
    for (i = 0; i < candidateInputs.length; i++){
      if ($.inArray(candidateInputs[i].value, candidateInputsArr) == -1){
        candidateInputsArr.push(candidateInputs[i].value);
      }
    }
    for (i = 0; i < candidateInputsArr.length; i++){
      candidates += candidateInputsArr[i] + ";";
    }

    candidates = candidates.slice(0, -1);
    if (name == "" || /[^a-z0-9]/.test(name)){
      document.getElementById("createElectionErrorDisplay").innerHTML = "Please enter a valid election name";
      return false;
    } else if (displayName == ""){ // Cy Added
      document.getElementById("createElectionErrorDisplay").innerHTML = "Please enter a valid election display name";
      return false;
    } else if (area == "" || /[^a-zA-Z ]/.test(area)){
      document.getElementById("createElectionErrorDisplay").innerHTML = "Please enter a valid election area";
      return false;
    } else if(date == ""){
      document.getElementById("createElectionErrorDisplay").innerHTML = "You must enter an end date for the election";
      return false;
    }else if (candidates == "" && type != "REF"){
      document.getElementById("createElectionErrorDisplay").innerHTML = "You must add candidates to an election";
      return false;
    }

    if (type == "REF"){
      candidates = "";
    }

    $.post("./addElection.php", { //post id to php
        name: name,
        displayName: displayName, // Cy Added
        type: type,
        area: area,
        date: date,
        candidates: candidates
      }, function(result) {
        if (result == ""){
          location.reload();
        } else {
          document.getElementById("createElectionErrorDisplay").innerHTML = "There is already an election with this name";
        }
      });

  }
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
