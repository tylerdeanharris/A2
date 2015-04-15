<?php
	// Start a session
	session_start();
	$email = $_SESSION['email'];
	$password = $_SESSION['password'];
	
	// Check to see if there is an active session
	if (!$email && !$password) {
		echo "Welcome Guest!<br /><a href=login.php>Login</a> | <a href=register.php>Register</a>";
	} else {
		echo "Welcome back, ".$email." (<a href=logout.php>Logout</a>)";
	}
?>