<?php
if(!empty($_POST["postid"])){

//Include DB configuration file
include("connect.php");

//Get last ID
$lastID = $_POST['postid'];


//Limit on data display
$showLimit = 5;

//Get all rows except already displayed
$queryAll = $conn->query("SELECT COUNT(*) as num_rows FROM posts WHERE postid < ".$lastID." ORDER BY postid ASC");
var_dump($queryAll);
exit;
$rowAll = $queryAll->mysqli_fetch_assoc();
$allNumRows = $rowAll['num_rows'];


//Get rows by limit except already displayed
$query = $conn->query("SELECT * FROM posts WHERE postid < ".$lastID." ORDER BY postid ASC LIMIT ".$showLimit);

if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){ 
        $postID = $row["postid"]; ?>
        
<div class="list-item"><h4><?php echo $row['title']; ?></h4></div>
<?php } ?>


<?php if($allNumRows > $showLimit){ ?>
    <div class="load-more" lastID="<?php echo $postID; ?>" style="display: none;">
        <img src="loading.gif"/>
    </div>
<?php }else{ ?>
    <div class="load-more" lastID="0">
        That's All!
    </div>
<?php }
}else{ ?>
    <div class="load-more" lastID="0">
        That's All!
    </div>
<?php
    }
}
?>
