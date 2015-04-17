<?php
	//Create a session
	session_start();
	//include("dbconnect.php");
	
	//Display the login form
	function index() {
		echo "<form action='login.php' method='post'>"
		."Email: <input type='text' name='email' size='30'><br>"
		."Password: <input type='password' name='password' size='30'><br>"
		."<input type='submit' value='Login' name='login'>"
		."</form>";
	}
	
	//This function will log the user in
	function login() {
		
		//Connect to the database
		include("dbconnect.php");
		
		//Collect user provided information
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		
		//Check if the user provided email is correct
		$sql = "SELECT * FROM users WHERE email='$email'";
		$result = $dbh->query($sql);
		$row = $result->fetch(PDO::FETCH_LAZY);
		$id = $row['id'];
		
		$sql2 = "SELECT * FROM users WHERE id='$id'";
		$result2 = $dbh->query($sql2);
		$row2 = $result2->fetch(PDO::FETCH_LAZY);
		$user = $row2['email'];
		   
		if ($email != $user) {
			die("Your login email was wrong! <a href=login.php>Back</a> to login.");
		}
			
		$sql3 = "SELECT * FROM users WHERE email='$email' AND id='$id'";
		$result3 = $dbh->query($sql3);
		$row3 = $result3->fetch(PDO::FETCH_LAZY);
		$real = $row3['password'];
		
		if ($password != $real) {
			die("Your login password was wrong! <a href=login.php>Back</a> to login.");
		}
		
		//Now if everything is correct let's finish his/her login
		$_SESSION["email"] = $email;
		$_SESSION["password"] = $password;
		
		echo "Welcome, ".$email." please continue on our <a href=index.php>Index</a>";
		$dbh->null;
		die();
		
	}
		
	if (isset($_POST['login'])) {
	   login();
	}

	index();
	
?>