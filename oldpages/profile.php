<?php

include ("connect.php");
require_once("authorise.php");
include("webpageTemplate.php");
$username = $_SESSION['username'];

?>

<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body>
	<h1 class="text-center"><?php echo htmlentities($username) ?></h1>
	<br>	
<div class="container-fluid">
	<div class="container-fluid text-left">
    	<div class="row">
    		<div class="col-3">
	
		<?php
		$query = "SELECT * FROM accounts WHERE username = '$username'";
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_assoc($result)) {
			$userid = $row['userid'];
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];
			$age = $row['age'];
			$gender = $row['gender'];
			$profileName = $row['profilename'];
			$profilePath = $row['profilepath'];
			$biography = $row['biography'];
			
			
			//PROFILE PICTURE PRINT & FORM SECTION
			echo"<form method='POST' action='profilePictureConfirm.php' enctype='multipart/form-data'>";
			//CHECK TO SEE IF A PROFILE PICTURE HAS BEEN SET
			if(!empty($profilePath)){
				echo"<img src='$userid/$profilePath' width='250'/> <br>";
			} else {
				echo"<img src='profile placeholder.png' width='250' /> <br>";
			}
			
			echo"<br><br><input type='file' name='profilePicture' required/><br>";
			echo"<input type='hidden' name='profilePictureName' value='profilePicture'>";
			echo"<br><input type='submit' value='Update Profile Picture'><br><br>";
			echo"</form>";
				
			
			//PROFILE TEXT PRINT & FORM SECTION
			echo"<form method='POST' action='profileConfirm.php' id='profileform'>";
			echo"First Name: <input type='text' name='firstName' placeholder='$firstName' required> <br><br>";
			echo"Last Name: <input type='text' name='lastName' placeholder='$lastName' required> <br><br>";
			echo"Age: <input type='number' name='age' placeholder='$age' required> <br><br>";
			echo"Gender: $gender <br><input type=radio name='gender' value='M' checked required> Male <br>";
			echo"<input type=radio name='gender' value='F' required> Female <br><br>";
			echo"Biography: <textarea form='profileform' name='biography' placeholder='$biography' rows='4' cols='50' required></textarea><br><br>";
			echo"<input type='submit' value='Update Profile'>";
			echo"</form>";
		}
		?>
		<p class="text-center"><a href="homepageLoggedIn.php">Back to homepage </a></p>
		</div>
	<div class="col-9 text-center">
	<!--- POSTS RELATING TO USER ONLY WILL GO HERE IN DATE DESCENDING ORDER --->
	<h1>NEW POST: </h1>
			<form action="postconfirm.php" method="POST" enctype="multipart/form-data">
				<input type="text" name="newPost" placeholder="new post goes here.." required>
				<br>
				<input type="file" name="postPicture">
				<br>
				<input type="hidden" name="postPictureName" value="postPicture">
				<br> 
				<input type="submit" value="POST IT!">
			</form>
			<br>
	<h1>PERSONAL POSTS: </h1>
	<?php
	$query = "SELECT accounts.userid, accounts.username,accounts.profilepath, posts.postid, posts.post, posts.postTimeCreated 
			FROM( accounts 
			INNER JOIN posts ON accounts.userid = posts.userid) 
			ORDER BY posts.postTimeCreated DESC LIMIT 5";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$userid = $row['userid'];
				$usernameDB = $row['username'];
				$profilePath = $row['profilepath'];
				$postid = $row['postid'];
				$post = $row ['post'];
				$postTime = $row['postTimeCreated'];
				
				if($usernameDB == $username){
										
					echo"<table align='center'>";
					echo"<tr>";
					if(!empty($profilePath)){
						echo"<td><img src='$userid/$profilePath' height='30'/><a href='profileOtherUser.php?user=$userid'>".$username."</a> ---->".$post."</td>";
					} else {
						echo"<td><img src='profile placeholder.png' height='30'/><a href='profileOtherUser.php?user=$userid'>".$username."</a> ---->".$post."</td>";
					}
					echo"</tr>";
					echo"<tr>";
					echo"<td>".$postTime."<br><br></td>";
					echo"</tr>";
					
					$query = "SELECT posttaglink.postid, posttaglink.tagid, tags.tagname
					FROM ((posttaglink
					INNER JOIN posts ON posts.postid = posttaglink.postid)
					INNER JOIN tags ON tags.tagid = posttaglink.tagid)";
					$result4 = mysqli_query($conn, $query);
					while($row4 = mysqli_fetch_assoc($result4)) {
						$tagname = $row4['tagname'];
						$posttaglinkid = $row4['postid'];
						
						if($postid == $posttaglinkid){
							echo"<tr>";
							echo"<td> TAG: <a href='tagOnlyPosts.php?tag=$tagname'>".$tagname."</a></td>";
							echo"</tr>";
						}
					}
					
					
					echo"</tr>";
					echo"<td>";
##########################//DELETE A POST SECTION ###############################
					echo"<form method='POST' action='deletePost.php'>";
					echo"<input type='hidden' name='postid' value='$postid'/>";
					echo"<input type='submit' value='DELETE POST' class='text-center'/>";
					echo"</form>";
					echo"</td>";
					
					
				echo"<tr>";
				echo"<td>LATEST COMMENTS</td>";
				echo"</tr>";	
				
			$query = "SELECT accounts.username, accounts.profilepath, posts.post, posts.postTimeCreated, comments.userID, comments.postID, comments.commentID, comments.comment, comments.commentDate 
					FROM ((comments 
					RIGHT JOIN accounts ON comments.userid = accounts.userid) 
					INNER JOIN posts ON comments.postID = posts.postID) 
					WHERE posts.postid = $postid
					ORDER BY comments.commentid DESC";
						$result3 = mysqli_query($conn, $query);
						while($row3 = mysqli_fetch_assoc($result3)){
						$comment = $row3['comment'];
						$commentUserid = $row3['userID'];
						$commentDate = $row3['commentDate'];
						$commentUsername = $row3['username'];
						$commentUserProfile = $row3['profilepath'];

							echo"<tr>"; 
							if(!empty($profilePath)){
								echo"<td><img src='$commentUserid/$commentUserProfile' height='30'/><a href='profileOtherUser.php?user=$commentUserid'>".$commentUsername."</a> ----> ".$comment." - ".$commentDate."<br></td>";
							} else {
								echo"<td><img src='profile placeholder.png' height='30'/><a href='profileOtherUser.php?user=$commentUserid'>".$commentUsername."</a> ----> ".$comment." - ".$commentDate."<br></td>";
							}
							echo"</tr>";
						}
					
							
							echo"<td>"; 
							echo"<form method='POST' action='commentConfirm.php'>";					
							echo"<input type='text' placeholder='insert comment...' name='newComment' required/>";
							echo"<input type='hidden' name='postUserid' value='$userid'/>";
							echo"<input type='hidden' name='postid' value='$postid'/>";
							echo"<input type='hidden' name='selectPost' value='$post'/>";
							echo"<input type='submit' value='comment!'/>";
							echo"<br>";		
							echo"<br></td>";
							echo"</table>";	
							echo"</form>";
				
				
				
				
				
				
				}
	}
	?>
	</div>
</div>
</div>		
</div>

</body>
<footer>
<?php mysqli_close($conn); ?>
</footer>
</html>
