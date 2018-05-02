<?php
require "../../includes/getConnElection.php";

$name = isset($_POST["name"]) ? $_POST["name"] : "";  //get area
$displayName = isset($_POST["displayName"]) ? $_POST["displayName"] : "";  //get area Cy Added
$type = isset($_POST["type"]) ? $_POST["type"] : "";  //get area
$area = isset($_POST["area"]) ? $_POST["area"] : "";  //get area
$date = isset($_POST["date"]) ? $_POST["date"] : "";  //get area
$candidates = isset($_POST["candidates"]) ? $_POST["candidates"] : "";  //get area

$sql = "
SELECT * FROM election
WHERE electionName = '" . $name . "'
";
$query = $conn->query($sql);
$num_rows = $query->rowCount();

if ($num_rows == 0){
  $sql = "
  INSERT INTO `election` (`electionName`, `electionType`, `electionArea`, `electionDate`, `electionDisplayName`, `electionCandidates`, `isEncrypted`)
  VALUES ('$name', '$type', '$area', '$date', '$displayName', '$candidates', 1)
  "; // Cy Added

  $query = $conn->query($sql);

  if ($type == "FPTP"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        candidateID VARCHAR(500),
        FOREIGN KEY (voterNIN) REFERENCES voter(Username),
        FOREIGN KEY (candidateID) REFERENCES candidate(candidateID)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1"; // VARCHAR(500) added for encryption support
  } elseif ($type == "STV"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        candidateIDs VARCHAR(500),
        FOREIGN KEY (voterNIN) REFERENCES voter(Username)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
  } elseif ($type == "REF"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        candidateID VARCHAR(500),
        FOREIGN KEY (voterNIN) REFERENCES voter(Username)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1"; // VARCHAR(500) added for encryption support 
  }                                            // & also changed the referendum choice to be called candidateID
  $query = $conn->query($sql);

} else {
  echo "An election with this name already exists";
}

$conn = null;

?>
