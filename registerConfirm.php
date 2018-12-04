<?php
include("connect.php");

$username = $_POST["username"];
$password = $_POST["password"];
//HASH AND SALT WITH THE USERNAME BEFORE INSERT
$passwordHash = hash('sha256', $password.$username);

$query2 = "SELECT * FROM accounts where username = $username";
$result = mysqli_query($conn, $query2);
if(mysqli_num_rows($result) > 0) {
	$location = "registerError.php"; //couldn't get this to work! WAS SUPPOSE TO ALERT ON SCREEN IF THE USERNAME WAS ALREADY IN USE
} else {

	$query = "INSERT INTO accounts (username, password) VALUES (?,?)";
		if(is_string($username)) {
			if($stmt = mysqli_prepare($conn,$query)) {
				mysqli_stmt_bind_param($stmt,"ss",$username,$passwordHash);
				mysqli_stmt_execute($stmt);
				mkdir(mysqli_stmt_insert_id($stmt));
				mysqli_stmt_close($stmt);
				$location = "homepagefinal.php";
			

		} else {
				echo mysqli_error($conn);
		} 
	}  
}	

header("Location: $location");
exit;

?>

<html>
	<head>
		<title>Confirmed Account</title>
	</head>
	<body>
		<!--<p>You have successfully created your account!</p> -->
	</body>
</html>
