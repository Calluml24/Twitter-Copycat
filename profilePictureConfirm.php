<?php
include("connect.php");
require_once("authorise.php");

$username = $_SESSION['username'];
//$profilePicture = $_POST['profilePicture'];
$profilePictureName = $_POST['profilePictureName'];

$query = "SELECT * FROM accounts";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
		$allUserid = $row['userid'];
		$usernamedb = $row['username'];
		if($usernamedb == $username) {
			$userid = $row['userid'];
	}
}



if(!empty($profilePictureName)){
	if($_FILES["profilePicture"]["size"] < 20000000) {
	if($_FILES["profilePicture"]["error"] > 0){
		echo "Picture error: ".$_FILES["profilePicture"]["error"]."<br>";
	} else {
		$filename = $_FILES["profilePicture"]["name"]; //should be setting to profilePicture
		$filetype = $_FILES["profilePicture"]["type"];
		
		$filename = "Profile Picture".$username; //set the profile picture to set within dir
		
		$uploaddir = $userid."/"; //sets variable = to userid/ (1/)
		
		
		$renamed = false;
		
		//if there is a profile picture file already
		if(file_exists($uploaddir.$filename)) {
			unlink($uploaddir.$filename);
			
			$filenamearray = explode(".",$filename);
			$filename = "";
			$filename .= "Profile Picture"/*.$username*/;
			$renamed = true;
			
			/*var_dump($filename);
			exit;*/
			
		}
		
		
		
		move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $uploaddir.$filename);
		
		if($renamed) {
			echo "Profile has been updated <br>";
		}
		
		
		
			$statement = "UPDATE accounts SET profilename = ?, profilepath = ? WHERE username = ?";
			if($stmt = mysqli_prepare($conn, $statement)) {
				mysqli_stmt_bind_param($stmt, "sss", $profilePictureName, $filename, $username);
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


header("Location: profilefinal.php");
?>

<html>
<head>
</head>
<body>
	<a href="profilefinal.php">View profile</a>
</body>
</html>
