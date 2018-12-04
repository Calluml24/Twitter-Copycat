<?php
include("connect.php");
require_once("authorise.php");
$username = $_SESSION['username'];
$selectPostid = $_POST['postid']; 

/*var_dump($selectPostid);
exit;
*/
//gets id for the user inputting the comment
/*$query = "SELECT * FROM accounts";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
		$allUserid = $row['userid'];
		$usernamedb = $row['username'];
		if($usernamedb == $username) {
			$userid = $row['userid']; 
		}
}*/


$query = "DELETE FROM posts WHERE postid = ?";
if($stmt = mysqli_prepare($conn, $query)) {
	mysqli_stmt_bind_param($stmt, "i", $selectPostid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	} else {
		echo mysqli_error($conn);
		}

echo"Post Deleted Successfully";
mysqli_close($conn);
?>
<html>
<body>
<p><a href="profilefinal.php">Back to profile</a></p>
</body>
</html>

