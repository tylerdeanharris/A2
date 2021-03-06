<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Contact Us - Townsville Community Music Centre</title>
    <meta name="keywords" content="Music-Townsville, Townsville music events, tcmc">
  	<meta name="description" content="Townsville Community Music Center.">
	<body>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                    	<h2>Contact Us</h2>
                        <p>We value your feedback here at Townsville Community Music Centre. Please fill in the form below and we'll get back to you as soon as possible.</p>
                        <form name="contactForm" method="POST" action="#">
                            <table width="400px">
                            	<tr>
                             		<td valign="top"><label for="first_name">First Name: *</label></td>
                             		<td valign="top"><input  type="text" name="first_name" maxlength="50" size="35"></td>
                            	</tr>
                            	<tr>
                             		<td valign="top"><label for="last_name">Last Name: *</label></td>
                                    <td valign="top"><input type="text" name="last_name" maxlength="50" size="35"></td>
                            	</tr>
                            	<tr>
                                	<td valign="top"><label for="email">Email Address: *</label></td>
                             		<td valign="top"><input type="text" name="email" maxlength="80" size="35"></td>
                            	</tr>
                             	<tr>
                                	<td valign="top"><label for="comments">Message: *</label></td>
                                    <td valign="top"><textarea  name="comments" maxlength="1000" cols="45" rows="10"></textarea></td>
                            	</tr>
                            	<tr>
                             		<td colspan="2" style="text-align:center"><input type="submit" value="Submit"></td>
                             	</tr>
                            </table>
                        </form>
                    </div>
                </article>
          </section>
          <aside>
              <div class="side-box">
                	<?php
						//Display the login form
						function index() {
							
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
								echo "<table><form action='index.php' method='post'><tr>"
								."<h1>Login</h1>"
								."<td>Email:</td><td><input type='text' name='email' id='inputemail'></td></tr>"
								."<tr><td>Password:</td><td><input type='password' name='password' id='inputpass'></td></tr>"
								."<tr><td><input type='submit' value='Login' name='login'></td></tr>"
								."<tr><td><small>Not a member?<a href='register.php'>Signup now!</a></small></td></tr>"
								."</form></table>";
							} else {
								$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
								$result = $dbh->query($getUserInfo);
								$row = $result->fetch(PDO::FETCH_LAZY);
								if ($row['membership_type'] == 0) {
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
								} else if ($row['membership_type'] == 1) {
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
								} else {
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href='adminPanel.php'>Control Panel</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
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
          <?php include("inc/sponsor.php"); ?>	
          <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>