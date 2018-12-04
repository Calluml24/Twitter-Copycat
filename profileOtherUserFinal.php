<?php
include("webpageTemplate.php");
include("functions.php");
$postUserid = $_GET['user'];
?>

<div class="container-fluid main h-100">
	<div class="row">
		<div class="col-sm-3">
	<?php
		$query = "SELECT * FROM accounts WHERE userid = '$postUserid'";
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
			//CHECK TO SEE IF A PROFILE PICTURE HAS BEEN SET
			if(!empty($profilePath)){
				echo"<img class='profilepic' src='$userid/$profilePath' width='250'/> <br>";
			} else {
				echo"<img class='profilepic' src='profile placeholder.png' width='250' /> <br>";
			}
			
			echo"<br>";
			
			//PROFILE TEXT PRINT & FORM SECTION
			echo"First Name: <input type='text' name='firstName' placeholder='$firstName' disabled> <br><br>";
			echo"Last Name: <input type='text' name='lastName' placeholder='$lastName' disabled> <br><br>";
			echo"Age: <input type='number' name='age' placeholder='$age' disabled> <br><br>";
			echo"Gender: $gender <br><br>";
			echo"Biography: <textarea form='profileform' name='biography' placeholder='$biography' rows='4' cols='50' disabled></textarea><br><br>";
		}
	?>
		</div>
		<div class="col-sm-9">
				<?php

		
$query = "SELECT accounts.userid, accounts.username,accounts.profilepath, posts.postid, posts.post, posts.postTimeCreated
FROM( accounts 
INNER JOIN posts ON accounts.userid = posts.userid) 
ORDER BY posts.postTimeCreated DESC";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)) {
	$userid = $row['userid'];
	$usernameDB = $row['username'];
	$profilePath = $row['profilepath'];
	$postid = $row['postid'];
	$post = $row ['post'];
	$postTime = $row['postTimeCreated'];
	
	if($userid == $postUserid){

$query = "SELECT * FROM posts WHERE posts.postid = $postid";
		$result2 = mysqli_query($conn, $query);
		while($row2 = mysqli_fetch_assoc($result2)) {
			$filepath = $row2['filepath'];
			$filename = $row2['filename'];
		
			//STARTS THE CARD CREATION 
			
			//GET THE IMAGE OF A POST IF IT WAS ATTACHED TO ONE 
			if(!empty($filename)){
				echo"<div class='card mx-auto'>";
				echo"<img class='card-img-top' src='$userid/$filepath'/>";
				echo"</div>";
			}
			
			echo"<div class='card mx-auto'>";
			//PROCESS TAGS ATTACHED TO THE POST
					
			
				//GENERATES A CARD-BODY FOR POST INFORMATION, IF STATEMENT TO FIND IF PROFILE PIC IS SET AND USE PLACEHOLDER IF NOT
					echo"<div class='card-header'>";
					if(!empty($profilePath)) {
						echo"<h5 class='card-title'><img class='posterPic' src='$userid/$profilePath'/>&emsp;<a href='profileOtherUserFinal.php?user=$userid'>".$usernameDB."</a></h5>
						<p class='postTime'><small class='text-muted postTime'>".time_elapsed_string($postTime)."</small></p>";
						echo"</div>";
						echo"<p class='card-text posts'>".$post."</p>";
					} else {
						echo"<h5 class='card-title'><img class='posterPic'src='profile placeholder.png'/><a href='profileOtherUserFinal.php?user=$userid'>".$usernameDB."</a></h5>
						<p class='postTime'><small class='text-muted postTime'>".time_elapsed_string($postTime)."</small></p>";
						echo"</div>";
						echo"<p class='card-text posts'>".$post."</p>";
					}
					
					
					echo"<div class='card-body'>";
					//PROCESS THE TAG SECTION
					$query = "SELECT posttaglink.postid, posttaglink.tagid, tags.tagname
					FROM ((posttaglink
					INNER JOIN posts ON posts.postid = posttaglink.postid)
					INNER JOIN tags ON tags.tagid = posttaglink.tagid)";
						$result4 = mysqli_query($conn, $query);
						while($row4 = mysqli_fetch_assoc($result4)) {
							$tagname = $row4['tagname'];
							$posttaglinkid = $row4['postid'];
							
							if($postid == $posttaglinkid){
								echo"<p class='card-text posts'><small class='text-muted'>Tag: <a href='tagOnlyPostsFinal.php?tag=$tagname'> ".$tagname."</a></small></p>";
							}
					}		
					
						//STARTS THE COMMENTS SECTION
						//echo"<ul class='list-group list-group-flush'>";
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
							
							if(!empty($commentUserProfile)){
								echo"<ul class='list-group list-group-flush'>";
								echo"<li class='list-group-item'><img class='commentPic' src='$commentUserid/$commentUserProfile'/>
								<a href='profileOtherUserFinal.php?user=$commentUserid'>".$commentUsername."</a> ".$comment."<small class='text-muted'> ".time_elapsed_string($commentDate)."</small><br></li>";
							} else {
								echo"<ul class='list-group list-group-flush'>";
								echo"<li class='list-group-item'><img class='commentPic' src='profile placeholder.png'/>
								<a href='profileOtherUserFinal.php?user=$commentUserid'>".$commentUsername."</a> ".$comment."<small class='text-muted'> ".time_elapsed_string($commentDate)."</small><br></li>";
							}
						echo"</ul>";
						}
					//FINISH THE CARD BODY SECTION	
					echo"</div>";
					
					//SECTION FOR NEW COMMENT
					echo"<div class=card-footer>";
						echo"<form method='POST' action='commentConfirm.php' align='center'>";
						echo"<input type='text' class='commenttext' placeholder='insert comment...' name='newComment' required/>";
						echo"<input type='hidden' name='postUserid' value='$userid'/>";
						echo"<input type='hidden' name='postid' value='$postid'/>";
						echo"<input type='hidden' name='selectPost' value='$post'/>";
						echo"<input type='submit' class='btn commentbtn' value='comment!'/>";
						echo"</form>";
					echo"</div>";
		}
			//FINISH THE CARD 
			echo"</div>";
			echo"<br><br>";
	}
}
?>

<?php
include("footer.php");
?>
