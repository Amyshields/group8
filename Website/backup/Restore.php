<?php 
$dump_path = "";  //your backup location
$host = "";   //host
$user = "";  //username
$pass = "";  //password
$command=$dump_path.'mysql -h '.$host.' -u '.$user.' bank < bank.sql';
system($command);
?>