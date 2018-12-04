<?php

//Details for connecting to mySQL database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "friendzone";

//Create the intital connection to the database
$conn = mysqli_connect($servername,$username,$password,$dbname);

//Check to see if the connection was made
if (!$conn) 
{
    die("Connection has failed: ".mysqli_connect_error());
}


?>
