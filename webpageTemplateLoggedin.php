<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous" defer></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
<script src="scripts.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	
<nav class="navbar navbar-expand-sm navbar-light fixed-top">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	 
	<a class="navbar-brand logo" href="homepagefinalloggedin.php">FriendZone</a>
	
	<div class="navbar-collapse collapse text-white" id="navbarNav">
		<ul class="nav navbar-nav text-white">
			<?php	
		if(isset($_SESSION['id'])) {
			echo"<li class='nav-item'>";
				echo"<a class='nav-link text-white' href='profilefinal.php'>PROFILE</a>";
			echo"</li>";
				
				echo"<li class='nav-item'>";
					echo"<a class='nav-link text-white' href='logout.php'>LOGOUT</a>";
				echo"</li>";
		} else {
				echo"<li class='nav-item'>";
					echo"<a class='nav-link text-white' href='' data-toggle='modal' data-target='#myModal2'>LOGIN</a>";
				echo"</li>";
				echo"<li class='nav-item'>";
					echo"<a class='nav-link text-white' href='register.php'>REGISTER</a>";
				echo"</li>";
				
				}	
				
				
				?>
		</ul>
		<ul class="nav navbar-nav text-white ml-auto">			
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					TOP TRENDS
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<?php	
				//populate users here
				$query = "SELECT tags.tagname, COUNT(posttaglink.tagid) AS total 
				FROM posttaglink 
				LEFT JOIN tags ON posttaglink.tagid = tags.tagid 
				GROUP BY tagname 
				ORDER BY total DESC LIMIT 5";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_assoc($result)){
					$tagname = $row['tagname'];
					
					echo"<a class='dropdown-item' href='tagOnlyPostsFinalloggedin.php?tag=$tagname'>".$tagname."</a>";
					echo"<div class='dropdown-divider'></div>";
				}
				?>
				</div>
			</li>
				
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					LATEST USERS
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<?php	
				//populate users here
				$query = "SELECT * FROM accounts
				ORDER BY accountCreated DESC LIMIT 5";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_assoc($result)) {
					$username = $row['username'];
					$userid = $row['userid'];
				
					echo"<a class='dropdown-item' href='profileOtherUserFinalloggedin.php?user=$userid'>".$username."</a>";
					echo"<div class='dropdown-divider'></div>";
				}
				?>
				</div>
			</li>
		</ul>
		<form class="nav navbar-nav form-inline" action="tagOnlyPostsFinalloggedin.php">
			<input class="form-control mr-sm-2" type="text" name="tag" placeholder="Search a tag..." pattern="[a-z]{1,30}" title="tags should not contain numbers or spaces and be lowercase only.">
			<button class="btn searchbtn" type="submit">Search</button>	
		</form>
	</div>
</nav>

<!---MODAL FOR THE LOGIN POP OUT SECTION--->
<div class="modal fade" id="myModal2">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header justify-content-center" >
				<h2 class="modal-title">LOGIN</h2>
				<button type="button" class="close" data-dismiss="modal">&times;</button> <!--- has the x close icon on it--->
			</div>
			
			<div class="modal-body">
				<h3>Please enter your login details below</h3>
					<form action="authenticateLogin.php" method="POST" align="center">
					Username: <br>
					<input type="text" name="username" required><br><br>
					Password: <br>
					<input type="password" name="password" required><br><br>
					<meter max="4" id="password-strengh"></meter>
					<p id="password-strength-text"></p> 
					
					<!---<input type="submit" value="POST IT!"> --->
			</div>
			<!-- Modal footer -->
			<div class="modal-footer justify-content-center">
				<input type="submit" value="LOGIN" class="btn postbtn" >
				</form>
			</div>
		</div>
	</div>
</div>


<br><br><br><br>

<!---SETUP A BASIC MOCK OF A NEW POST AS A PERMENANT MODAL CLICK U	NDERNEATH TITLE --->
<div class="d-flex justify-content-center modalrow	">
<div class="col-4">
<button type="button" class="btn modalbtn btn-lg btn-block" data-toggle="modal" data-target="#myModal">
  NEW POST!
</button>
<!-- Modal Setup -->
	<div class="modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">

      <!-- Modal Header -->
			<div class="modal-header justify-content-center" >
				<h2 class="modal-title">Make a new post</h2>
				<button type="button" class="close" data-dismiss="modal">&times;</button> <!--- has the x close icon on it--->
			</div>

      <!-- Modal body -->
			<div class="modal-body">
				<form action="postconfirm.php" method="POST" enctype="multipart/form-data" align="center">
					<input class="posttext" type="text" name="newPost" placeholder="new post goes here.." required>
					<br><br>
					<h4>Optional photo & tag </h4>
					<br>
					<input type="file" name="postPicture" class="btn postbtn"/>
					<br><br>
					<input class="posttext" type="text" name="tagName" placeholder="enter a tag.. (i.e cricket)" pattern="[a-z]{1,30}" title="tags should not contain numbers or spaces and be lowercase only." required/> <!--- reg = no whitespaces, lowercase only, less that 30 --->
					<input type="hidden" name="postPictureName" value="postPicture"/>
					<!---<input type="submit" value="POST IT!"> --->
			</div>

      <!-- Modal footer -->
			<div class="modal-footer justify-content-center">
				<input type="submit" value="POST" class="btn postbtn" >
				</form>
			</div>

			</div>
		</div>
	</div>
</div>
</div>

<br>

<div class="container-fluid main h-100">
	<div class="row">
		<div class="col-sm-12 ">
<!--- THESE WILL NEED TO BE CLOSED INCLUDING CLOSING A CONNECTION WITHIN A FOOTER FOR EACH PAGE --->

				





