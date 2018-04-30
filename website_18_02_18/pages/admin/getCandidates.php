<?php
require "../../includes/getConnCandidate.php";

$area = isset($_POST["area"]) ? $_POST["area"] : "";  //get area

if ($area == "National"){
  $sql = "
  SELECT *
  FROM candidate
  ";
} else{
  $sql = "
  SELECT *
  FROM candidate
  WHERE candidateArea = '" . $area . "'
  ";
}

$query = $conn->query($sql);
$num_rows = $query->rowCount();

$returnCandidates = array();
if ($num_rows === 0){
  echo "Error";
} else {
  foreach ($query as $row){
    $candInfo = $row['candidateID'] . ";" . $row['candidateName'] . ";" . $row['candidateParty'] . ";" . $row['candidateArea'];
    array_push($returnCandidates, $candInfo);
  }
  echo json_encode($returnCandidates);
}


 ?>
