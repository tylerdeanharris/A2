<?php
	include("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Home - Townsville Community Music Centre</title>
    <meta name="keywords" content="Music-Townsville, Townsville music events, tcmc">
  	<meta name="description" content="Townsville Community Music Center.">
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
          <section id="main-content">
                         
            <article>
                	<div>
                    	<h1>Newest Bulletin Post:</h1>
                        <?php
							$idQuery = $dbh->query("SELECT MAX(id) as max_id FROM bulletin_board");
							$idQueryResult = $idQuery->fetch(PDO::FETCH_ASSOC);
							
							$bulletinQuery = $dbh->query("SELECT * FROM bulletin_board WHERE id = $idQueryResult[max_id]");
							$bulletinResult = $bulletinQuery->fetch(PDO::FETCH_ASSOC);
							
							$createdByQuery = $dbh->query("SELECT first_name, last_name FROM users WHERE id='$bulletinResult[created_by]'");
							$createdByResult = $createdByQuery->fetch(PDO::FETCH_ASSOC);
							
							echo '<h1>' . $bulletinResult['title'] . '</h1>';
							echo "<div><img width='250px' height='200px' src='" . $bulletinResult['location'] . "'></div>";
							echo '<p>' . $bulletinResult['body'] . '</p>';
							echo '<small>Created By: ' . $createdByResult['first_name'] . ' ' . $createdByResult['last_name'] . '</small><br />';
							echo '<small>Contact Details: ' . $bulletinResult['contact_preference'] . '</small>';
						?>
                	</div>
                </article>
                <br />
                <article>
                	<div>
                    	<h1>Newest Event:</h1>
                        <?php
							$idQuery = $dbh->query("SELECT MAX(id) as max_id FROM events");
							$idQueryResult = $idQuery->fetch(PDO::FETCH_ASSOC);
							
							$eventQuery = $dbh->query("SELECT * FROM events WHERE id = $idQueryResult[max_id]");
							$eventResult = $eventQuery->fetch(PDO::FETCH_ASSOC);
							
							$createdByQuery = $dbh->query("SELECT first_name, last_name FROM users WHERE id='$eventResult[created_by]'");
							$createdByResult = $createdByQuery->fetch(PDO::FETCH_ASSOC);
							
							echo '<h1>' . $eventResult['title'] . '</h1>';
							echo "<div><img width='250px' height='200px' src='" . $eventResult['location'] . "'></div>";
							echo '<p>' . $eventResult['information'] . '</p>';
							echo '<small>Created By: ' . $createdByResult['first_name'] . ' ' . $createdByResult['last_name'] . '</small><br />';
							echo '<small>Venue: ' . $eventResult['venue'] . '</small>';
						?>
                	</div>
                </article>
                <br />
                <article>
                	<div>
                    	<h1>Newest Artist:</h1>
                        <?php
							$idQuery = $dbh->query("SELECT MAX(artist_id) as max_id FROM artistInput");
							$idQueryResult = $idQuery->fetch(PDO::FETCH_ASSOC);
							
							$artistQuery = $dbh->query("SELECT * FROM artistInput WHERE artist_id = $idQueryResult[max_id]");
							$artistResult = $artistQuery->fetch(PDO::FETCH_ASSOC);
							
							echo '<h1>' . $artistResult['artist_name'] . '</h1>';
							echo "<div><img width='250px' height='200px' src='" . $artistResult['location'] . "'></div>";
							echo '<p>' . $artistResult['artist_description'] . '</p>';
							echo '<small>Music Genre: ' . $artistResult['music_genre'] . '</small>';
						?>
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