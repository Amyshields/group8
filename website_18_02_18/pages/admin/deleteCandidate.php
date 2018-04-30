<?php
require "../../includes/getConnCandidate.php";

$id = isset($_POST["id"]) ? $_POST["id"] : "";  //get id

$sql = "
DELETE candidate
FROM candidate
WHERE candidateId = $id
";
$query = $conn->query($sql);
?>
