<?php
	session_start();
	include("dbconnect.php");
	if(!isset($_SESSION['id'])) {
		header("Location: bulletin.php");
	}
	
	$deletePost = "DELETE FROM bulletin_board WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($deletePost))
		header("Location: bulletin.php");
	else
		die("Error while deleting bulletin post...");
?>