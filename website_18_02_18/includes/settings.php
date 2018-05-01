<?php
/*Noah Johnson C1649499*/

$local = false; //Do you want to connect to local or online server?

//Details for the online server (Don't change)
$oservername = "csmysql.cs.cf.ac.uk"; //Name of the online server
$odbname = "group8_2017"; //Database on the server to enter
$odbusername = "group8.2017"; //Database login username
$odbpassword = "kMB8PRzvnZf7Amg"; //Database password username

//Details for the local server
$lservername = "127.0.0.1"; //Name of the local server (wamps standard)
$ldbname = "group8_2017"; //Database on the server to enter
$ldbusername = "root"; //Database login username (wamp's standard is 'root')
$ldbpassword = "root"; //Database password username	(wamp's standard is nothing)


/*
-----------------DEBUG SECTION-----------------
Q: How can I recereate the database on a local server to test data
without disrupting the online data?

A: Install wamp server (https://www.youtube.com/watch?v=MsMaiHqkKkQ)
then create a new database at localhost/phpmyadmin called 'group8_2017'.
After this, import the dummy data called 'dummyDataFilledDatabase.sql'.
Finally, change the $local variable to true and it should work.


Q: Set $local to true and cannot login in using voter login page?

A: Check the capitalisation of your table's names, wamp uses lowercase
tables names while the online version's start with a capital.
-----------------------------------------------
*/

/*
//Details for the online server (Don't change)
$oservername = "csmysql.cs.cf.ac.uk"; //Name of the online server
$odbname = "group8_2017"; //Database on the server to enter
$odbusername = "group8.2017"; //Database login username
$odbpassword = "kMB8PRzvnZf7Amg"; //Database password username

//Details for the local server
$lservername = "127.0.0.1:3306"; //Name of the local server (wamps standard)
$ldbname = "group8_2017"; //Database on the server to enter 
$ldbusername = "root"; //Database login username (wamp's standard is 'root')
$ldbpassword = ""; //Database password username	(wamp's standard is nothing) 
*/
?>
