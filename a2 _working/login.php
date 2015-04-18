<?php
	//Create a session
	session_start();
	
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
		$password = md5($_REQUEST['password']);
		
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
		
		//Quickly collect all other user data for use during their session
		$sql = "SELECT * FROM users WHERE id='$id'";
		$result = $dbh->query($sql);
		$row4 = $result->fetch(PDO::FETCH_LAZY);
		
		$first_name = $row['first_name'];
		$last_name = $row4['last_name'];
		$address = $row4['address'];
		$phone_day = $row4['phone_day'];
		$phone_after_hours = $row4['phone_after_hours'];
		$mobile = $row4['mobile'];
		$membership_type = $row4['membership_type'];
		
		//Now if everything is correct let's finish his/her login
		$_SESSION["first_name"] = $first_name;
		$_SESSION["last_name"] = $last_name;
		$_SESSION["email"] = $email;
		$_SESSION["address"] = $address;
		$_SESSION["phone_day"] = $phone_day;
		$_SESSION["phone_after_hours"] = $phone_after_hours;
		$_SESSION["mobile"] = $mobile;
		$_SESSION["password"] = $password;
		$_SESSION["membership_type"] = $membership_type;
		
		header("Location: index.php");
		$dbh->null;
		die();
		
	}
		
	if (isset($_POST['login'])) {
	   login();
	}

	index();
	
?>