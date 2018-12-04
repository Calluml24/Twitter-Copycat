<?php
//PAGINATION IDEA TAKEN FROM - https://www.webslesson.info/2016/05/how-to-make-simple-pagination-using-php-mysql.html
include("connect.php");

$post_per_page = 5;
$page ='';

if(isset($_GET["page"])) {
		$page = $_GET["page"];
} else {
	$page = 1;
}

$start_from = ($page-1)*$post_per_page;

?>
