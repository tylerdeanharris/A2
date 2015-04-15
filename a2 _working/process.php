<?php
	include("dbconnect.php");
	
	// Collect the user provided data
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	
	// Check for empty fields
	if (empty($email)) {
		die("Please enter an email address!<br>");
	}
	
	if (empty($password)) {
		die("Please enter a password!<br>");
	}
	
	// Check if the user provided email address is valid
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		die("The provided email address is not valid");
	}
	
	// Check if the user provided email address is already in use
	
	$sql = "SELECT * FROM users WHERE email='$email'";
	$result = $db->query($sql);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	$id = $row['id'];
	
	$sql2 = "SELECT * FROM users WHERE id='$id'";
	$result2 = $db->query($sql2);
	$row2 = $result2->fetchArray(SQLITE3_ASSOC);
	$user = $row2['email'];
	   
	if ($email === $user) {
		die("Email already in use...");
	}
	
	// Encrypt the password with MD5
	$passProtect = md5($_REQUEST[password]);
	
	$sql = "INSERT INTO users (email, password) VALUES ('$_REQUEST[email]', '$passProtect')";
	if ($dbh->exec($sql)) {
		echo "Your registration was successfully processed. Welcome, $_REQUEST[email]. You can now <a href=login.php>login</a>. Go <a href=index.php>home</a>.";
	} else {
		echo "There was a problem trying to process your registration. Please try again.";
	}
?>