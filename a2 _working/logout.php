<?php
	// Start a session
	session_start();
	/*$email = $_SESSION['email'];
	$password = $_SESSION['password'];*/
	
	/*if(!$email && !$password) {*/
	if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
		header("Location: index.php");
	} else {
		session_destroy();
		echo "Successfully logged out! <a href=index.php>Home</a>";
		die();
	}
?>