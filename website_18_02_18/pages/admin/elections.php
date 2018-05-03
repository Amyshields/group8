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
      <p><h2>Create Election</h2></p>
      <style>
      td {
        padding: 3px;
      }
      </style>
      <table>
      <div id="addElection">
        <tr><td>Election Name: </td><td><input type="text" name="newName" id="newName"></td></tr>
        <!-- Cy Added -->
        <tr><td>Election Display Name: </td><td><input type="text" name="newDisplayName" id="newDisplayName"></td></tr>
        <tr><td>Election Type:</td><td>
        <select name="newType" id="newType">
          <option value="FPTP">FPTP</option>
          <option value="STV">STV</option>
          <option value="REF">Referendum</option>
        </select>
        </td></tr>
        <tr><td>Election Area: </td><td><input type="text" name="newArea" id="newArea"></td></tr>
        <tr><td>Election Date: </td><td><input type="date" name="newDate" id="newDate"></td></tr>
        <tr><td colspan="2"><div id="addCands"></div></td></tr>
        <tr><td colspan="2"><button type="button" id="addCandidateBtn" onclick="addCandidate()">Add Candidate</button>
        <button type="button" id="newCandidateBtn" onclick="newCandidate()">+</button>
        <button type="submit" onclick="createElection()">Create Election</button>
        <button type="button" onclick="location.reload()">Clear</button></td></tr>
        <tr><td colspan="2"><div id="createElectionErrorDisplay"></div></td></tr>
      </div>
    </table>

      <p><h2>View Election Settings</h2></p>
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

            echo "<table>";
            echo "<tr><td>Election Name: </td><td>$electionName</td></tr>";
            echo "<tr><td>Election Display Name: </td><td>$electionDisplayName</td></tr>"; // Cy Added
            echo "<tr><td>Election Type: </td><td>$electionType</td></tr>";
            echo "<tr><td>Election Area: </td><td>$electionArea</td></tr>";
            echo "<tr><td>Election Date: </td><td>$electionDate</td></tr>";
            if ($electionIsEncrypted==1){ // Cy Added
              echo "<tr><td>Encrypted: </td><td>YES</td></tr>";
            } else {
              echo "<tr><td>Encrypted: </td><td>NO</td></tr>";
            }


            if ($electionType != "REF"){
              echo "<tr><td colspan=2>Election Candidates: <table>";
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
                    echo "<tr><td>Name: $candName </td><td>Party: $candParty </td><td>Area: $candArea</td></tr>";

                }
              }
              echo "</td></tr></table>";
            }
            echo "<tr><td><button id='delete$electionId' type='submit' onclick='deleteElection($electionId)'>Delete Election</button></td></tr></table><br>";
          }
        } else {
            $_SESSION['error'] = "Couldn't fetch results, check debug section of settings.php";
            //redirect('../index.php');
        }
        $conn = null;
      ?>
      <footer class="container-fluid text-left">
		<!--info here: logo, copyright, links, login as admin-->
		</br>
		<ul>
			<li><a href="#">Help</a></li>
			<li><p>Other links</p></li>
			<li><a href="index.php">Back</a></li>
			<li><p> &copy; 2018, Group 8. All rights reserved.</p></li>
		</ul>
	</footer>
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
