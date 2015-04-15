<?php
	// Start a session
	session_start();
	include("dbconnect.php");
	
	// Collect the user provided data
	$email = $_REQUEST['email'];
	$password = md5($_REQUEST['password']);
	
	// Validate the user provided data
	$sql = "SELECT * FROM users WHERE email='$email'";
	$result = $db->query($sql);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	$id = $row['id'];
	
	$sql2 = "SELECT * FROM users WHERE id='$id'";
	$result2 = $db->query($sql2);
	$row2 = $result2->fetchArray(SQLITE3_ASSOC);
	$user = $row2['email'];
	   
	if ($email != $user) {
		die("Your login email was wrong! <a href=login.php>Back</a> to login.");
	}
		
	$sql3 = "SELECT * FROM users WHERE email='$email' AND id='$id'";
	$result3 = $db->query($sql3);
	$row3 = $result3->fetchArray(SQLITE3_ASSOC);
	$real = $row3['password'];
	
	if ($password != $real) {
		die("Your login password was wrong! <a href=login.php>Back</a> to login.");
	}
	
	// If the login was processed successfully, create a valid session with the users details and redirect to the home page automatically
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	header("Location: index.php");
	$db->close();
?>