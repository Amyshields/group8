<?php
require "../../includes/getConnElection.php";

$id = isset($_POST["id"]) ? $_POST["id"] : "";  //get id

$sql = "
SELECT electionName
FROM election
WHERE electionID = $id
";
$query = $conn->query($sql);

$electionInfo = $query->fetchAll();
$electionName = $electionInfo[0]['electionName'];

$sql = "
DELETE election
FROM election
WHERE electionID = $id
";
$query = $conn->query($sql);

$sql = "
DROP TABLE $electionName
";
$query = $conn->query($sql);

?>
