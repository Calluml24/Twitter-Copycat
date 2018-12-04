<?php
include("connect.php");

$username = $_POST["username"];
$password = $_POST["password"];
$passwordHash = hash('sha256', $password.$username);

$query = "SELECT * FROM accounts WHERE username = ?";


if(is_string($username))	{
	if($stmt = mysqli_prepare($conn, $query))	{
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $userdb, $passdb, $fName, $lName, $age, $gender, $accountCreated, $profileName, $profilePath, $biography);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	} else {
		echo mysqli_error($conn);
	} 
} else {
	echo "Username is incorrect";	
}



if($passwordHash == $passdb) {
	if($username == $userdb)	{
		session_start();
		session_regenerate_id();
		$_SESSION['id'] = session_id();
		$_SESSION['username'] = $username;
		//$location = "homepageLoggedIn.php";
		$location = "homepagefinalloggedin.php";
	} 
}else {
	echo "LOGIN FAILED";
	$location = "homepage.php";
}
header("Location: $location");
exit;
?>
