<?php
		
	//This function will display the registration form
	function register_form() {
		echo "<form action='register.php' method='post'>"
		."First Name: <input type='text' name='first_name' size='40'><br>"
		."Last Name: <input type='text' name='last_name' size='40'><br>"
		."Email: <input type='text' name='email' size='40'><br>"
		."Address: <input type='text' name='address' size='40'><br>"
		."Phone (Daytime): <input type='number' name='phone_day' size='40'><br>"
		."Phone (After Hours): <input type='number' name='phone_after_hours' size='40'><br>"
		."Mobile: <input type='number' name='mobile' size='40'><br>"
		."Password: <input type='password' name='password' size='40'><br>"
		."Confirm your password: <input type='password' name='password_conf' size='40'><br>"
		."<input type='submit' value='Register' name='submit'>"
		."</form>";
	}
	
	//This function will register the users data
	function register() {
		
		//Connect to the database
		try {
			/* connect to SQLite database. It's good to have this in a separate file you can include in all pages that need DB access */
			$dbh = new PDO("sqlite:a2.sqlite");
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
		// Collect the user information
		$first_name = $_REQUEST['first_name'];
		$last_name = $_REQUEST['last_name'];
		$email = $_REQUEST['email'];
		$address = $_REQUEST['address'];
		$phone_day = $_REQUEST['phone_day'];
		$phone_after_hours = $_REQUEST['phone_after_hours'];
		$mobile = $_REQUEST['mobile'];
		$password = $_REQUEST['password'];
		$pass_conf = $_REQUEST['password_conf'];
		
		// Check that all inputs have values in them
		if(empty($first_name)){
			die("Please enter your first name!<br>");
		}
		if(empty($last_name)){
			die("Please enter your last name!<br>");
		}
		if(empty($email)){
			die("Please enter your email address!<br>");
		}
		if(empty($address)){
			die("Please enter your postal address!<br>");
		}
		if(empty($phone_day)){
			die("Please enter your daytime contact number!<br>");
		}
		if(empty($phone_after_hours)){
			die("Please enter your after hours contact number!<br>");
		}
		$mobile_check = strlen((string)$mobile);
		if($mobile_check != 10){
			die("Please enter a valid mobile number and ensure there are no spaces, special characters or alphanumeric values!<br>");
		}
		if(empty($password)){
			die("Please enter a password!<br>");
		}
		if(empty($pass_conf)){
			die("Please confirm your password!<br>");
		}
		
		// Check if the user provided email address is valid
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die("Sorry, but it appears the email address you have entered is not valid.");
		}
		
		//Check if the email address is already in use
		$email_check = "SELECT email FROM users WHERE email='$email'";
		$result = $dbh->query($email_check);
		$do_email_check = $result->fetchObject();
		if ($do_email_check > 0) {
			die("Sorry, but it appears the email address you have entered is already registered to an account.");
		}
		
		//Secure the password with MD5 encryption
		$password = md5($password);
		$pass_conf = md5($pass_conf);
		
		//Check if passwords match
		if ($password != $pass_conf) {
			die("Passwords do not match");
		}
		
		//If everything above has passed, register the user
		$insert = "INSERT INTO users (first_name, last_name, email, address, phone_day, phone_after_hours, mobile, password) VALUES ('$first_name', '$last_name', '$email', '$address', '$phone_day', '$phone_after_hours', '$mobile', '$password')";
		if ($dbh->exec($insert)) {
			die("Congratulations " . $first_name . "! Your membership registration was procesed successfully.<br><br><a href=login.php>Login</a> | <a href=index.php>Index</a>");
		} else {
			die("Sorry, but it appears an error has occurred while processing your membership registration. If the problem persists, please contact us for assistance!");
		}
	}
	
	if(isset($_POST['submit']))
	{
	   register();
	}

	register_form();

?>