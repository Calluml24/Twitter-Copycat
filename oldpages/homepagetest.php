<?php 
include("webpageTemplate.php");
?>

<h1 align="center">NEW POST: </h1>
			
<form action="postconfirm.php" method="POST" enctype="multipart/form-data" align="center">
	<input type="text" name="newPost" placeholder="new post goes here.." required>
	<br><br>
	<input type="file" name="postPicture"/>
	<br>
	<input type="text" name="tagName" placeholder="enter a tag.. (i.e cricket)" pattern="[a-z]{1,30}" title="tags should not contain numbers and be lowercase only."/> <!--- reg = no whitespaces, lowercase only, less that 30 --->
	<input type="hidden" name="postPictureName" value="postPicture"/>
	<br>
	<br>
	<input type="submit" value="POST IT!">
</form>
<br>

<h1 align="center">LATEST POSTS: </h1>
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
	
	
		echo"<form method='POST' action='commentConfirm.php' align='center'>";
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
		echo"<img src='$userid/$filepath' height='200'/>";
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

?>




<?php
include("footer.php");
?>
