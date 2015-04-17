<?php
		
	//This function will display the registration form
	function register_form() {
		echo "<form action='register.php' method='post'>"
		."Email: <input type='text' name='email' size='30'><br>"
		."Password: <input type='password' name='password' size='30'><br>"
		."Confirm your password: <input type='password' name='password_conf' size='30'><br>"
		."<input type='submit' value='Register' name='submit'>"
		."</form>";
	}
	
	//This function will register the users data
	function register() {
		
		//Connect to the database
		try {
			/* connect to SQLite database. It's good to have this in a separate file you can include in all pages that need DB access */
			$dbh = new PDO("sqlite:a2.sqlite");
			echo "success<br>";
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
		// Collect the user information
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$pass_conf = $_REQUEST['password_conf'];
		
		// Check that all inputs have values in them
		if(empty($email)){
			die("Please enter your email!<br>");
		}
		if(empty($password)){
			die("Please enter your password!<br>");
		}
		if(empty($pass_conf)){
			die("Please confirm your password!<br>");
		}
		
		// Check if the user provided email address is valid
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die("The provided email address is not valid");
		}
		
		//Check if the email address is already in use
		$email_check = "SELECT email FROM users WHERE email='$email'";
		$result = $dbh->query($email_check);
		$do_email_check = $result->fetchObject();
		
		//Display any errors that may have occured
		if ($do_email_check > 0) {
			die("Email address is already in use");
		}
		
		//Check if passwords match
		if ($password != $pass_conf) {
			die("Passwords do not match");
		}
		
		//If everything above has passed, register the user
		$insert = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
		echo "<p>Query: " . $insert . "</p>\n<p><strong>";
		if ($dbh->exec($insert)) {
			die("Inserted into database successfully");
		} else {
			die(print_r($dbh->exec($insert)));
		}	
		echo "You are now registered. Thank you!<br><a href=login.php>Login</a> | <a href=index.php>Index</a>";
		die();
	}
	
	if(isset($_POST['submit']))
	{
	   register();
	}

	register_form();
?>