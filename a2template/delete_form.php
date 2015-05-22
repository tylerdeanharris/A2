<?php
	session_start();
	include("dbconnect.php");
	if ($_REQUEST['submit'] == "X"){
		$sql = "DELETE FROM artistInput WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($sql))
		header("Location: artist.php"); // NOTE: This must be done before ANY html is output, which is why it's right at the top!
	}
	if (!isset($_SESSION['id'])) {
		header("Location: artist_list.php");
	} else if ($_SESSION['membership_type'] != 1) {
		header("Location: artist_list.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
	<title>Delete Artist - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                    <?php                        
                    // Display what's in the database at the moment.
                    $sql = "SELECT * FROM artistInput";
                    
                    foreach ($dbh->query($sql) as $row){
                        echo "<form id='deleteForm' name='deleteForm' method='post' action='delete_form.php'/>"
                        ."<label>Artist Name:</label>";
                            echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
                        ."<label>Artist Description:</label>";
                            echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
                        ."<label>Email:</label>";
                            echo "<input type='text' name='email' value='$row[email]' />"
                        ."<label>Genre:</label>";
                            echo "<input type = 'text' name music_genre  value='$row[music_genre]' />";	
                            echo "<label>Image:</label>"
                        .'<p><img width="300px" height="300px" src="'.$row['location'].'"></p>';
                            echo "<input type='hidden' name='artist_id' value='$row[artist_id]' />";
                    
                        echo "<input type='submit' name='submit' value='Delete Artist' class='deleteButton'/>"
                        ."</form>";
                    }
                    if ($_REQUEST['submit'] == "Delete Artist"){
                        $sql = "DELETE FROM artistInput WHERE artist_id = '$_REQUEST[artist_id]'";
                        if ($dbh->exec($sql))
                            echo "<p>Query: " . $sql . "</p>\n<p><strong>";
                            header("Location: delete_form.php");
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
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>