<?php
	// Start a session
	session_start();
	
	if (!isset($_SESSION['id'])) {
		header("Location: index.php");
	} else {
		session_destroy();
		header("Location: index.php");
	}
?>