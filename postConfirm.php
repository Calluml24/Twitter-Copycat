<?php

include("connect.php");
require_once("authorise.php");

$username = $_SESSION['username'];
$newPost = $_POST['newPost'];
$picturename = $_POST['postPictureName'];
$tagname = $_POST['tagName'];

/*This query is designed to get the userID for the user that is currently 
in use to insert into posts table alongside post to maintain relationship*/
$query = "SELECT * FROM accounts";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
		$allUserid = $row['userid'];
		$usernamedb = $row['username'];
		if($usernamedb == $username) {
			$userid = $row['userid'];
		}
}	

$statement = "INSERT INTO posts(userid, post) VALUES(?, ?)"; 
	if(is_string($newPost)){
		if ($stmt = mysqli_prepare($conn, $statement)) {
			mysqli_stmt_bind_param($stmt, "is", $userid, $newPost);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			
		}else {	
			echo mysqli_error($conn);
		} echo "Post uploaded successfully";
	}

$lastPostid = mysqli_insert_id($conn); //this is used to reference the lastid of the post


#####//image upload to logged in user directory#########
if(!empty($_FILES["postPicture"])){
	if($_FILES["postPicture"]["size"] < 20000000) {
		if($_FILES["postPicture"]["error"] > 0){
			//echo "Picture error: ".$_FILES["postPicture"]["error"]."<br>"; // this would give same error as !empty 
		} else {
		$filename = $_FILES["postPicture"]["name"];
		$filetype = $_FILES["postPicture"]["type"];
		
		$uploaddir = $userid."/"; //this will upload image into the users photo folder
		
		$i = 0;
		$renamed = false;
		while(file_exists($uploaddir.$filename)) {
			$filenamearray = explode(".",$filename);
			$filename = "";
			
			for($i=0;$i<count($filenamearray)-1;$i++) {
				$filename .= $filenamearray[$i].".";
			}
			
			$filename .= date("Y-m-d-H-i-s").".";
			$filename .= end($filenamearray);
			$renamed = true;
		}
		
		move_uploaded_file($_FILES["postPicture"]["tmp_name"], $uploaddir.$filename);
		
		if($renamed) {
			echo "File exists already. Renamed file to $filename <br>";
		}
		
		
		
		$statement = "UPDATE posts SET filename = ?, filepath = ? WHERE postid = $lastPostid";
			if($stmt = mysqli_prepare($conn, $statement)) {
				mysqli_stmt_bind_param($stmt, "ss", $picturename, $filename);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				
				echo "File uploaded successfully";
			} else {
				echo mysqli_error($conn);
			} 
		}
	} else {
		echo "File is too large to upload<br>";
		}
} else {
	echo "No file was sent over";
	}


############################//TAG PROCESSING SECTION ##################################
//get all data from the database on tags
if(!empty($tagname)) {
	$statement = "INSERT INTO tags (tagname) VALUES (?)";
	if($stmt = mysqli_prepare($conn, $statement)) {
		mysqli_stmt_bind_param($stmt, "s", $tagname);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		echo "tag added";
	}
	
	$query = "SELECT * FROM tags";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_assoc($result)){
		$allTagids = $row['tagid'];
		$tagnamedb = $row['tagname'];
		if($tagname == $tagnamedb){
			$tagid = $row['tagid'];
		}
	}
	
	//have the tables linked together
	$statement = "INSERT INTO posttaglink(postid, tagid) VALUES (?,?)";
	if($stmt = mysqli_prepare($conn, $statement)) {
		mysqli_stmt_bind_param($stmt, "ii", $lastPostid, $tagid);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}	
//look to rerun a search to obtain the postid just made?
mysqli_close($conn);

header("Location: homepagefinalloggedin.php");
exit;
?>
<html>
<body>
<p><a href="homepagefinalloggedin.php">See Post</a></p>
</body>
</html>
