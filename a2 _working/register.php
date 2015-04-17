<?php
		
	//This function will display the registration form
	function register_form() {
		$date = date('D, M, Y');
		echo "<form action='register.php' method='post'>"
		."Email: <input type='text' name='email' size='30'><br>"
		."Password: <input type='password' name='password' size='30'><br>"
		."Confirm your password: <input type='password' name='password_conf' size='30'><br>"
		."<input type='hidden' name='date' value='$date'>"
		."<input type='submit' value='Register' name='submit'>"
		."</form>";
	}
	
	//This function will register the users data
	function register() {
		
		//Connect to the database
		try {
			$dbh = new PDO('sqlite:a2.sqlite');
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
		// Collect the user information
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$pass_conf = $_REQUEST['password_conf'];
		$date = $_REQUEST['date'];
		
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
		if ($dbh->exec($insert))
			echo "Inserted into database successfully<br><br>";
		else
			echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable
			
		echo "You are now registered. Thank you!<br><a href=login.php>Login</a> | <a href=index.php>Index</a>";
		die();
	}
	
	if(isset($_POST['submit']))
	{
	   register();
	}

	register_form();
?>