<?php
require "../../includes/getConnCandidate.php";

$id = isset($_POST["id"]) ? $_POST["id"] : "";  //get id
$name = isset($_POST["name"]) ? $_POST["name"] : "";  //get name
$party = isset($_POST["party"]) ? $_POST["party"] : "";  //get party
$area = isset($_POST["area"]) ? $_POST["area"] : "";  //get area

$sql = "
UPDATE `$table`
  SET
  candidateName = '$name',
  candidateParty = '$party',
  candidateArea = '$area'
  WHERE `candidateID` = '$id'
";
$query = $conn->query($sql);
?>
