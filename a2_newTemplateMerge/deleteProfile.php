<?php
	session_start();
	include("dbconnect.php");
	if(!isset($_SESSION['id'])) {
		header("Location: index.php");
	}
	
	$deleteProfile = "DELETE FROM users WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($deleteProfile))
		header("Location: logout.php");
	else
		die("Error while deleting profile...");
?>