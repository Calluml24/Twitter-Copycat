<?php

include ("connect.php");
include("webpageTemplate.php");
$tagname = $_GET['tag'];


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
<br><br>
<div class="container-fluid">
	<div class="container-fluid text-left">
    	<div class="row">
	<div class="col text-center" align="center">
	<!--- POSTS RELATING TO TAG ONLY WILL GO HERE IN DATE DESCENDING ORDER --->
	
	<?php
	$query = "SELECT accounts.*, posts.*, posttaglink.*, tags.*
			FROM accounts
			JOIN posts ON posts.userid = accounts.userid
			JOIN posttaglink ON posttaglink.postid = posts.postid
			JOIN tags ON tags.tagid = posttaglink.tagid 
			ORDER BY posts.postTimeCreated DESC LIMIT 5";
			$result = mysqli_query($conn, $query);
			while($row = mysqli_fetch_assoc($result)) {
				$userid = $row['userid'];
				$usernameDB = $row['username'];
				$profilePath = $row['profilepath'];	
				$postid = $row['postid'];
				$post = $row ['post'];
				$postTime = $row['postTimeCreated'];
				$tagnamedb = $row['tagname'];
				
				if($tagname == $tagnamedb){
				//echo"<h1>$usernameDB POSTS: </h1>";						
					echo"<table align='center'>";
					echo"<tr>";
					if(!empty($profilePath)){
						echo"<td><img src='$userid/$profilePath' height='30'/><a href='profileOtherUser.php?user=$userid'>".$usernameDB."</a> ---->".$post."</td>";
					} else {
						echo"<td><img src='profile placeholder.png' height='30'/><a href='profileOtherUser.php?user=$userid'>".$usernameDB."</a> ---->".$post."</td>";
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
					
					#####################//IMAGE PROCESS NEEDS TO OCCUR HERE####################################
			$query = "SELECT * FROM posts WHERE posts.postid = $postid";
					$result2 = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_assoc($result2)) {
						$filepath = $row2['filepath'];
						$filename = $row2['filename'];
					
					if(!empty($filename)){
					echo"<img src='$userid/$filepath' height='200' />";
					}
					
					
					
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
