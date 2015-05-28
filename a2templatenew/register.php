<?php
	session_start();
	
	// If a user is already logged in, they don't need to access this page
	if ($_SESSION['id']) {
		header("Location: index.php?error=invalidAccess");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Home</title>
        <!-- <link rel="stylesheet" href="reset.css" type="text/css" /> -->
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
    	<div id="main-wrapper">
            <header>
            
                <h1 class="logo"><a href="index.php" title="Home">Home</a></h1>
                
                <nav class="primary">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="bulletin.php">Bulletin</a></li>
                        <li><a href="#">Link 3</a></li>
                        <li><a href="#">Link 4</a></li>
                        <li><a href="#">Link 5</a></li>
                    </ul>
                </nav>
                
            </header>
            <section id="main-content">
                <article id="shit">
                        <?php
							//This function will display the registration form
							function register_form() {
								if (isset($_REQUEST['error'])) {
									if ($_REQUEST['error'] == 'noFirstName') {
										$_REQUEST['error'] == '';
										echo "<p class='loginError'>We require a first name.</p>";
									}
									if ($_REQUEST['error'] == 'noLastName') {
										$_REQUEST['error'] == '';
										echo "<p class='loginError'>We require a last name.</p>";
									}
								}
								echo "<form action='register.php' method='post'>"
								."<h1>Membership Registration</h1>"
								."* All fields are required<br><br>"
								."First Name: <input type='text' name='first_name' size='40' value='" . $_SESSION['register_first_name'] . "'><br>"
								."Last Name: <input type='text' name='last_name' size='40' value='" . $_SESSION['register_last_name'] . "'><br>"
								."Email: <input type='text' name='email' size='40'><br>"
								."Address: <input type='text' name='address' size='40'><br>"
								."Phone (Daytime): <input type='number' name='phone_day' size='40'><br>"
								."Phone (After Hours): <input type='number' name='phone_after_hours' size='40'><br>"
								."Mobile: <input type='number' name='mobile' size='40'><br>"
								."Password: <input type='password' name='password' size='40'><br>"
								."Confirm your password: <input type='password' name='password_conf' size='40'><br>"
								."<input type='submit' value='Register' name='submit' onclick='clearBox('shit')'>"
								."</form>";
							}
							
							//This function will register the users data
							function register() {
								
								//Connect to the database
								include("dbconnect.php");
								
								// Collect the user information
								$first_name = $_REQUEST['first_name'];
								$_SESSION['register_first_name'] = $first_name;
								$last_name = $_REQUEST['last_name'];
								$_SESSION['register_last_name'] = $last_name;
								$email = $_REQUEST['email'];
								$address = $_REQUEST['address'];
								$phone_day = $_REQUEST['phone_day'];
								$phone_after_hours = $_REQUEST['phone_after_hours'];
								$mobile = $_REQUEST['mobile'];
								$password = $_REQUEST['password'];
								$pass_conf = $_REQUEST['password_conf'];
								
								// Check that all inputs have values in them
								if (empty($first_name)) {
									header("Location: register.php?error=noFirstName");
									exit;
								}
								if (empty($last_name)) {
									header("Location: register.php?error=noLastName");
									exit;
								}
								if(empty($email)){
									die("Please enter your email address!<br><a href='register.php'>Back</a>");
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
									echo "Congratulations " . $first_name . "! Your membership registration was procesed successfully.<br><br><a href=login.php>Login</a> | <a href=index.php>Index</a>";
								} else {
									echo "Sorry, but it appears an error has occurred while processing your membership registration. If the problem persists, please contact us for assistance!";
								}
							}
							
							if(isset($_POST['submit']))
							{
							   register();
							}
						
							register_form();
						
						?>
                </article>
            </section>
            
            <aside>
                <div class="side-box">
                	<?php
						//Display the login form
						function index() {
							
							include("dbconnect.php");
							
							if (isset($_REQUEST['error'])) {
								if ($_REQUEST['error'] == '1') {
									echo "<p class='loginError'>Sorry, but that email address does not exist in our system.</p>";
								}
								if ($_REQUEST['error'] == '2') {
									echo "<p class='loginError'>Sorry, but it appears that password was wrong.</p>";
								}
								if ($_REQUEST['error'] == '3') {
									echo "<p class='loginError'>Sorry, but it appears the associated password was incorrect.</p>";
								}
							}	
							if (!$_SESSION['id']) {
								echo "<table><form action='index.php' method='post'><tr>"
								."<h1>Login</h1>"
								."<td>Email:</td><td><input type='text' name='email' size='30'></td></tr>"
								."<tr><td>Password:</td><td><input type='password' name='password' size='30'></td></tr>"
								."<tr><td><input type='submit' value='Login' name='login'></td></tr>"
								."<tr><td><small>Not a member?<a href='register.php'>Signup now!</a></small></td></tr>"
								."</form></table>";
							} else {
								$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
								$result = $dbh->query($getUserInfo);
								$row = $result->fetch(PDO::FETCH_LAZY);
								if ($row['membership_type'] == 0) {
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
								} else {
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=#>Bulletin Board</a>)</p><p>(<a href=#>Artists</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
								}
							}
						}
						
						//This function will log the user in
						function login() {
							
							//Connect to the database
							include("dbconnect.php");
							
							//Collect user provided information
							$email = $_REQUEST['email'];
							$password = md5($_REQUEST['password']);
							
							if (empty($email)) {
								header("Location: index.php?error=3");
								exit;
							}
							
							//Check if the user provided email is correct
							$emailQuery = "SELECT email FROM users WHERE email='$email'";
							$emailResult = $dbh->query($emailQuery);
							$row = $emailResult->fetch(PDO::FETCH_LAZY);
							$dbEmail = $row['email'];
							   
							if ($email != $dbEmail) {
								header("Location: index.php?error=1");
							} else {
								
							$passwordQuery = "SELECT password FROM users WHERE email='$email'";
							$passwordResult = $dbh->query($passwordQuery);
							$row2 = $passwordResult->fetch(PDO::FETCH_LAZY);
							$dbPassword = $row2['password'];
							
							if ($password != $dbPassword) {
								header("Location: index.php?error=2");
							} else {
							
							//Quickly collect all other user data for use during their session
							$userInformation = "SELECT * FROM users WHERE email='$email' AND password='$password'";
							$userResult = $dbh->query($userInformation);
							$row3 = $userResult->fetch(PDO::FETCH_LAZY);
							
							$_SESSION["id"] = $row3['id'];
							$_SESSION["membership_type"] = $row3['membership_type'];
							
							header("Location: index.php");
							$dbh->null;
							}
							}
						}
							
						if (isset($_POST['login'])) {
						   login();
						}
					
						index();
						
					?>
                </div>
            </aside>
            
            <footer>
                <small>&copy; <a href="index.php" title="Townsville Community Music Centre">Townsville Community Music Centre</a>. All rights reserved.</small>
            </footer>
        </div>
	</body>
</html>