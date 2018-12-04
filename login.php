<?php
include("connect.php");

?>

<html>
<head>
	<title>LOGIN TO FRIENDZONE </title>
</head>	
	
<body>
	<h3>Please enter your login details below</h3>
	<form action="authenticateLogin.php" method="POST">
	Username: <br>
	<input type="text" name="username" required><br><br>
	Password: <br>
	<input type="password" name="password" required><br><br>
	<input type="submit" value="LOGIN">
	</form>
	<p><a href="register.php">New Account?</a></p>
	<p><a href="homepagetestLogged.php">Back to homepage</a></p>
</body>
</html>
