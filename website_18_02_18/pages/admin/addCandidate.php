<?php
require "../../includes/getConnCandidate.php";

$name = $_POST["newName"];
$party = $_POST["newParty"];
$area = $_POST["newArea"];

$sql = "
INSERT INTO `candidate` (`candidateName`, `candidateParty`, `candidateArea`)
VALUES ('$name', '$party', '$area')
";
$query = $conn->query($sql);

header('Location: ./candidates.php');
?>
