<?php

session_start();
$_SESSION = array();
session_destroy();


header("Location: homepagefinal.php");
exit;
?>

<html>
<head>
	<title>LOGOUT</title>
</head>
<body>
<h3>You have successfully logged out</h3>
<p><a href="homepagefinal.php">Click here to go back to homepage</a></p>
</body>
</html>
