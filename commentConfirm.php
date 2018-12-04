<?php
include("connect.php");
require_once("authorise.php");

$username = $_SESSION['username']; //username of current logged in user
$selectPost = $_POST['selectPost']; //post which has been selected
$selectPostid = $_POST['postid']; //the id of the post selected
$selectPostUserid = $_POST['postUserid']; //post user id of selected post
$newComment = $_POST['newComment']; //comment that has been made on post

/*var_dump($selectPostid);
var_dump($selectPost);
exit;*/

//gets id for the user inputting the comment
$query = "SELECT * FROM accounts";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
		$allUserid = $row['userid'];
		$usernamedb = $row['username'];
		if($usernamedb == $username) {
			$userid = $row['userid']; //now access the userid for who is making comment
	}
}


//the comment that has sent over, needs to be saved with the postid referenced from it

$query = "INSERT INTO comments (userid, postid, comment) VALUES (?,?,?)";
if(is_string($newComment)) {
	if($stmt = mysqli_prepare($conn,$query)) {
		mysqli_stmt_bind_param($stmt, "iis", $userid, $selectPostid, $newComment);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	} else {
		echo mysqli_error($conn);
	}
} else {
	echo "Something went wrong, please try again";	
}

mysqli_close($conn);

header("Location: homepagefinalloggedin.php");
exit;


?>
<html>
<body>
<p><a href="homepagefinalloggedin.php">See Comment</a></p>
</body>
</html>
