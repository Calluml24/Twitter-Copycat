<?php
include ("connect.php");
require_once("authorise.php");

$username = $_SESSION['username'];
$firstname = $_POST['firstName'];
$lastname = $_POST['lastName'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$biography = $_POST['biography'];


/*var_dump($firstname, $lastname, $age);
exit;*/

/*
$query = "UPDATE accounts SET firstName=?,lastName=?,age=?,gender=?, biography=? WHERE username = ?";
if($stmt = mysqli_prepare($conn, $query)) {
	mysqli_stmt_bind_param($stmt, "ssisss", $firstname, $lastname, $age, $gender, $biography, $username);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
*/	

//TRYING TO HAVE IT ONLY UPDATE A SECTION IF IT HAS BEEN SET FROM THE FORM REQUEST
if(!empty($firstname)){
	$query2 = "UPDATE accounts SET firstName=? WHERE username = ?";
	if($stmt = mysqli_prepare($conn, $query2)) {
		mysqli_stmt_bind_param($stmt, "ss", $firstname, $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} 

if(!empty($lastname)){
	$query3 = "UPDATE accounts SET lastName=? WHERE username = ?";
	if($stmt = mysqli_prepare($conn, $query3)) {
		mysqli_stmt_bind_param($stmt, "ss", $lastname, $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} 

if(!empty($age)){
	$query4 = "UPDATE accounts SET age=? WHERE username = ?";
	if($stmt = mysqli_prepare($conn, $query4)) {
		mysqli_stmt_bind_param($stmt, "is", $age, $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} 

if(!empty($gender)){
	$query5 = "UPDATE accounts SET gender=? WHERE username = ?";
	if($stmt = mysqli_prepare($conn, $query5)) {
		mysqli_stmt_bind_param($stmt, "ss", $gender, $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} 

if(!empty($biography)){
	$query6 = "UPDATE accounts SET biography=? WHERE username = ?";
	if($stmt = mysqli_prepare($conn, $query6)) {
		mysqli_stmt_bind_param($stmt, "ss", $biography, $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
} 
	//}
mysqli_close($conn);

header("Location: profilefinal.php");

?>

<html>
<head>
	<title>Profile Updated</title>
</head>
<body>
	<p>Your profile has been updated</p>
	<p><a href="profilefinal.php">BACK TO PROFILE</a></p>
</body>
</html>
