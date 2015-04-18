<?php
	// Start a session
	session_start();
	$name = $_SESSION['first_name'];
	$email = $_SESSION['email'];
	$password = $_SESSION['password'];
	
	// Check to see if there is an active session
	if (!$email && !$password) {
		echo "Welcome Guest!<br /><a href=login.php>Login</a> | <a href=register.php>Register</a>";
	} else {
		echo "Welcome back, ".$name.".<br>(<a href=profile.php>Profile</a>)<br>(<a href=logout.php>Logout</a>)";
	}
?>