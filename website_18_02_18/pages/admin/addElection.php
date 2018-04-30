<?php
require "../../includes/getConnElection.php";

$name = isset($_POST["name"]) ? $_POST["name"] : "";  //get area
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
  INSERT INTO `election` (`electionName`, `electionType`, `electionArea`, `electionDate`, `electionCandidates`)
  VALUES ('$name', '$type', '$area', '$date', '$candidates')
  ";

  $query = $conn->query($sql);

  if ($type == "FPTP"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        candidateID INT(11),
        FOREIGN KEY (voterNIN) REFERENCES voter(Username),
        FOREIGN KEY (candidateID) REFERENCES candidate(candidateID)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
  } elseif ($type == "STV"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        candidateIDs VARCHAR(11),
        FOREIGN KEY (voterNIN) REFERENCES voter(Username)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
  } elseif ($type == "REF"){
    $sql = "
    CREATE TABLE $name (
        voterNIN VARCHAR(9) PRIMARY KEY,
        result BOOL NOT NULL DEFAULT '0',
        FOREIGN KEY (voterNIN) REFERENCES voter(Username)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
  }
  $query = $conn->query($sql);

} else {
  echo "An election with this name already exists";
}

?>
