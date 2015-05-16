<?php
	session_start();
	include("dbconnect.php");
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
                <article>
                    <div>
                        <?php
							if(isset($_SESSION['id'])) {
								echo '<a href=addBulletin.php>Add New Post</a>';
							}
							$sql = "SELECT * FROM bulletin_board";
							$create = date("d.m.y");
							$expire = new DateTime($create);
							$expire->modify('+30 day');
							
							foreach($dbh->query($sql) as $row) {
								$query = $dbh->query("SELECT first_name, last_name FROM users WHERE id='$row[created_by]'");
								$result = $query->fetch(PDO::FETCH_ASSOC);
								echo '<h1>' . $row["title"] . '	</h1>';
								echo '<p>' . $row['body'] . '</p>';
								echo '<small>Created By: ' . $result['first_name'] . ' ' . $result['last_name'] . '</small><br />';
								echo '<small>Creation Date: ' . $create . '</small><br />';
								echo '<small>Expiry Date: ' . $expire->format('d-m-Y') . '</small>';
							}
						?>
                    </div>
                </article>
            </section>
            
            <aside>
                <div class="side-box">
                	<?php
						//Display the login form
						function displayLogin() {
							
							include("dbconnect.php");
							
							if (isset($_REQUEST['error'])) {
								if ($_REQUEST['error'] == 'invalidEmail') {
									echo "<p class='loginError'>Sorry, but that email address does not exist in our system.</p>";
								}
								if ($_REQUEST['error'] == 'invalidPassword') {
									echo "<p class='loginError'>Sorry, but it appears that password was wrong.</p>";
								}
								if ($_REQUEST['error'] == 'noEmail') {
									echo "<p class='loginError'>It helps to enter an email address.</p>";
								}
							}	
							if (!$_SESSION['id']) {
								echo "<table><form action='bulletin.php' method='post'><tr>"
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
						function processLogin() {
							
							//Connect to the database
							include("dbconnect.php");
							
							//Collect user provided information
							$email = $_REQUEST['email'];
							$password = md5($_REQUEST['password']);
							
							if (empty($email)) {
								header("Location: index.php?error=noEmail");
								exit;
							}
							
							//Check if the user provided email is correct
							$emailQuery = "SELECT email FROM users WHERE email='$email'";
							$emailResult = $dbh->query($emailQuery);
							$row = $emailResult->fetch(PDO::FETCH_LAZY);
							$dbEmail = $row['email'];
							   
							if ($email != $dbEmail) {
								header("Location: index.php?error=invalidEmail");
							} else {
								
							$passwordQuery = "SELECT password FROM users WHERE email='$email'";
							$passwordResult = $dbh->query($passwordQuery);
							$row2 = $passwordResult->fetch(PDO::FETCH_LAZY);
							$dbPassword = $row2['password'];
							
							if ($password != $dbPassword) {
								header("Location: index.php?error=invalidPassword");
							} else {
							
							//Quickly collect all other user data for use during their session
							$userInformation = "SELECT * FROM users WHERE email='$email' AND password='$password'";
							$userResult = $dbh->query($userInformation);
							$row3 = $userResult->fetch(PDO::FETCH_LAZY);
							
							$_SESSION["id"] = $row3['id'];
							$_SESSION["membership_type"] = $row3['membership_type'];
							
							header("Location: bulletin.php");
							$dbh->null;
							}
							}
						}
							
						if (isset($_POST['login'])) {
						   processLogin();
						}
					
						displayLogin();
						
					?>
                </div>
            </aside>
            
            <footer>
                <small>&copy; <a href="index.php" title="Townsville Community Music Centre">Townsville Community Music Centre</a>. All rights reserved.</small>
            </footer>
        </div>
	</body>
</html>