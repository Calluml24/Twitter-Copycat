<?php
include("connect.php");
//require_once("authorise.php");


?>	

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>    

<title>FRIENDZONE HOMEPAGE</title>
</head>
        
<body>
    <ul class="nav nav-tabs nav-justified">
        <li class="nav-item">
        <a class="nav-link" href="profile.php"><span class="glyphicon glyphicon-log-in">PROFILE</span></a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#">FRIENDZONE</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="login.php">LOGIN</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="logout.php">LOGOUT</a>
        </li>
    </ul>
    
<div class="container-fluid">
    <br>
    
    <div class="container-fluid text-center">
    	<div class="row">
    		<div class="col-3">
			 Other Users to be populated here
			</div>
			
		<div class="col-9"> 
			<h1>NEW POST: </h1>
			

			<form action="postconfirm.php" method="POST" enctype="multipart/form-data">
				<input type="text" name="newPost" placeholder="new post goes here.." required>
				<br><br>
				<input type="file" name="postPicture"/>
				<input type="text" name="tagName" placeholder="enter a tag.. (i.e cricket)" pattern="[a-z]{1,30}" title="tags should not contain numbers and be lowercase only."/> <!--- reg = no whitespaces, lowercase only, less that 30 --->
				<input type="hidden" name="postPictureName" value="postPicture"/>
				<br>
				<br>
				<input type="submit" value="POST IT!">
			</form>
			<br><br>
			<h1>LATEST POSTS: </h1>
			<?php 
			
			
#####################//POSTS WHILE LOOP SECTION//######################################			
			//First query gets the users & accounts to be referenced
			$query = "SELECT accounts.userid, accounts.username,accounts.profilepath, posts.postid, posts.post, posts.postTimeCreated
			FROM( accounts 
			INNER JOIN posts ON accounts.userid = posts.userid) 
			ORDER BY posts.postTimeCreated DESC LIMIT 5";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$userid = $row['userid'];
				$username = $row['username'];
				$profilePath = $row['profilepath'];
				$postid = $row['postid'];
				$post = $row ['post'];
				$postTime = $row['postTimeCreated'];
				
				
					echo"<form method='POST' action='commentConfirm.php'>";					
					echo"<table align='center'>";
					echo"<tr>";
					
					//USING HREF TO SEND A VARIABLE VIA HTTP GET REQUEST IN BROWSER TO REFERENCE THAT USERS INFORMATION
					if(!empty($profilePath)) {
						echo"<td><img src='$userid/$profilePath' height='30'/><a href='profileOtherUser.php?user=$userid'>".$username."</a> ---->".$post."</td>";
					} else {
						echo"<td><img src='profile placeholder.png' height='30'/><a href='profileOtherUser.php?user=$userid'>".$username."</a> ---->".$post."</td>";
					}
						
					echo"</tr>";
					echo"<tr>";
					echo"<td>".$postTime."</td>";
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
					
					
					echo"<tr>";
#####################//IMAGE PROCESS NEEDS TO OCCUR HERE####################################
			$query = "SELECT * FROM posts WHERE posts.postid = $postid";
					$result2 = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_assoc($result2)) {
						$filepath = $row2['filepath'];
						$filename = $row2['filename'];
					
					if(!empty($filename)){
					echo"<img src='$userid/$filepath' height='200' />";
					}
			
			
#####################//COMMENTS WHILE LOOP SECTION//######################################
					echo"<td>LATEST COMMENTS</td>";
					echo"</tr>";	
	
			
			$query = "SELECT accounts.username, accounts.profilepath, posts.post, posts.postTimeCreated, comments.userID, comments.postID, comments.commentID, comments.comment, comments.commentDate 
					FROM ((comments 
					RIGHT JOIN accounts ON comments.userid = accounts.userid) 
					INNER JOIN posts ON comments.postID = posts.postID) 
					WHERE posts.postid = $postid
					ORDER BY comments.commentid ASC";
						$result3 = mysqli_query($conn, $query);
						while($row3 = mysqli_fetch_assoc($result3)){
						$comment = $row3['comment'];
						$commentUserid = $row3['userID'];
						$commentDate = $row3['commentDate'];
						$commentUsername = $row3['username'];
						$commentUserProfile = $row3['profilepath'];

							echo"<tr>"; 
							if(!empty($filename)){
								echo"<td><img src='$commentUserid/$commentUserProfile' height='30'/><a href='profileOtherUser.php?user=$commentUserid'>".$commentUsername."</a> ----> ".$comment." - ".$commentDate."<br></td>";
							} else {
								echo"<td><img src='profile placeholder.png' height='30'/><a href='profileOtherUser.php?user=$commentUserid'>".$commentUsername."</a> ----> ".$comment." - ".$commentDate."<br></td>";
							}
							echo"</tr>";
						}
							echo"<td>"; 					
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
			mysqli_close($conn);
			?>			
					
		</div>
    	</div>
    </div> 
    <br>
    
</div>
</body>
</html>
