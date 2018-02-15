<?php 
$dump_path = ""; //input location for the backup to be saved
$host = "";  //db host e.g.- localhost 
$user = "";  //user e.g.-root
$pass = "";  //password
$command=$dump_path.'mysqldump -h '.$host.' -u '.$user.' bank > bank.sql';
system($command);
?> 