<?php

//this is soley used to check the session key, to allow for persistant login
//will take to login if unsuccessful

session_start();

if($_SESSION['id'] != session_id() || empty($_SESSION['username']))
	{
		header("Location: homepagefinal.php");
		exit;
	} 
?>
