<?php
	session_start();
	include("dbconnect.php");
	if(!isset($_SESSION['id']) || $_SESSION['membership_type'] != 2) {
		header("Location: events.php");
	}
	
	$deleteEvent = "DELETE FROM events WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($deleteEvent))
		header("Location: events.php");
	else
		die("Error while deleting the event...");
?>