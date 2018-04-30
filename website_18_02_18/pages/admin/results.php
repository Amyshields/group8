<?php
require "../../includes/getConnElection.php";

$sql_select = "
SELECT * FROM election
";
$query = $conn->query($sql_select);
$num_rows = $query->rowCount();
$electionData = $query->fetchAll();

if ($num_rows > 0){
  foreach ($electionData as $row) {
    $name = $electionData['electionName'];

    

  }
}

 ?>
