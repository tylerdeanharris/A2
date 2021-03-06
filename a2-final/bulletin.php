<?php
	session_start();
	include("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Bulletin Board - Townsville Community Music Centre</title>
    <meta name="keywords" content="Music-Townsville, Townsville music events, tcmc, Bulletin Board">
  	<meta name="description" content="Townsville Community Music Center.">
	<body>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
          <section id="main-content">
          	<article>
                    <div>
                        <?php
							if(isset($_SESSION['id'])) {
								echo '<a href=addBulletin.php>Add New Post</a>';
							}
							
							$sql = "SELECT * FROM bulletin_board";
							$currentDate = date("d.m.Y");
							$currentDate = new DateTime($currentDate);
							foreach($dbh->query($sql) as $row) {
								$dbExpiryDate = new DateTime($row['date_expires']);
								if($currentDate <= $dbExpiryDate) {
									$query = $dbh->query("SELECT first_name, last_name FROM users WHERE id='$row[created_by]'");
									$result = $query->fetch(PDO::FETCH_ASSOC);
									echo '<h1>' . $row["title"] . '</h1>';
									echo "<div><img width='250px' height='200px' src='" . $row['location'] . "'></div>";
									echo '<p>' . $row['body'] . '</p>';
									echo '<small>Created By: ' . $result['first_name'] . ' ' . $result['last_name'] . '</small><br />';
									echo '<small>Contact Details: ' . $row['contact_preference'] . '</small><br />';
									echo '<small>Posted On: ' . $row['date_created'] . '</small>';
									if($_SESSION['id'] == $row['created_by'] || $_SESSION['membership_type'] == 2) {
										echo '<br /><br /><a href="editBulletin.php?id=' . $row['id'] . '">Edit</a> | <a href="deleteBulletin.php?id=' . $row['id'] . '">Delete</a>';
									}
								} else {
									$remove = "DELETE FROM bulletin_board WHERE id = '$row[id]'";
									if($dbh->exec($remove)) {
										
									}
								}
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
								."<td>Email:</td><td><input type='text' name='email'></td></tr>"
								."<tr><td>Password:</td><td><input type='password' name='password'></td></tr>"
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
						function processLogin() {
							
							//Connect to the database
							include("dbconnect.php");
							
							//Collect user provided information
							$email = $_REQUEST['email'];
							$password = md5($_REQUEST['password']);
							
							if (empty($email)) {
								header("Location: bulletin.php?error=noEmail");
								exit;
							}
							
							//Check if the user provided email is correct
							$emailQuery = "SELECT email FROM users WHERE email='$email'";
							$emailResult = $dbh->query($emailQuery);
							$row = $emailResult->fetch(PDO::FETCH_LAZY);
							$dbEmail = $row['email'];
							   
							if ($email != $dbEmail) {
								header("Location: bulletin.php?error=invalidEmail");
							} else {
								
							$passwordQuery = "SELECT password FROM users WHERE email='$email'";
							$passwordResult = $dbh->query($passwordQuery);
							$row2 = $passwordResult->fetch(PDO::FETCH_LAZY);
							$dbPassword = $row2['password'];
							
							if ($password != $dbPassword) {
								header("Location: bulletin.php?error=invalidPassword");
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
          <?php include("inc/sponsor.php"); ?>	
          <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>