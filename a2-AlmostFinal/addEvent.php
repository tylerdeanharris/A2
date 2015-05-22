<?php
	session_start();
	include("dbconnect.php");
	if (!$_SESSION['id']) {
		header("Location: events.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Add Event - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                        <?php
							function displayForm() {
								include("dbconnect.php");
								if (isset($_REQUEST['error'])) {
									if ($_REQUEST['error'] == 'noTitle') {
										echo "<p class='loginError'>Please enter the name of the event.</p>";
									} else if ($_REQUEST['error'] == 'noInformation') {
										echo "<p class='loginError'>Please enter some content for the event.</p>";
									} else if ($_REQUEST['error'] == 'noLocation') {
										echo "<p class='loginError'>Please provide the event location.</p>";
									}
								}
						?>
                        <table>
                            <form method="post" action="addEvent.php">
                                <tr>
                                    <h1>Add New Event</h1>
                                    <td>Title:</td>
                                    <td><input type="text" name="title" size="43" autofocus="autofocus" /></td>
                                </tr>
                                <tr>
                                    <td>Information:</td>
                                    <td><textarea name="information" rows="10" cols="40"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Location:</td>
                                    <td><input name="location" size="43"/></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" value="Create Event" name="addEvent" /></td>
                                </tr>
                            </form>
                        </table>
                    <?php
						}
						function processForm() {
							//Connect to the database
							include("dbconnect.php");
							
							if(empty($_POST['title'])) {
								header("Location: addEvent.php?error=noTitle");
								exit();
							}
							if(empty($_POST['information'])) {
								header("Location: addEvent.php?error=noInformation");
								exit();
							}
							if(empty($_POST['location'])) {
								header("Location: addEvent.php?error=noLocation");
								exit();
							}
							
							$title = $_POST['title'];
							$information = $_POST['information'];
							$location = $_POST['location'];
							$date_created = date("d.m.y");
							$expire = new DateTime($date_created);
							$expire->modify('+30 day');
							$expire = $expire->format('d-m-Y');
							
							//If everything above has passed, create the post
							$insertEvent = "INSERT INTO events (title,information,location,date_created,date_expires,created_by) VALUES ('$title','$information','$location','$date_created','$expire','$_SESSION[id]')";
							if ($dbh->exec($insertEvent)) {
								header("Location: events.php");
							} else {
								echo "Sorry, but it appears something went wrong...";
							}
							
						}
							
						if (isset($_POST['addEvent'])) {
						   processForm();
						}
					
						displayForm();
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
                            echo "<table><form action='events.php' method='post'><tr>"
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
                                echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)</p><p>";
                            } else {
                                echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=#>Bulletin Board</a>)</p><p>(<a href=#>Artists</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
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
                            header("Location: events.php?error=noEmail");
                            exit;
                        }
                        
                        //Check if the user provided email is correct
                        $emailQuery = "SELECT email FROM users WHERE email='$email'";
                        $emailResult = $dbh->query($emailQuery);
                        $row = $emailResult->fetch(PDO::FETCH_LAZY);
                        $dbEmail = $row['email'];
                           
                        if ($email != $dbEmail) {
                            header("Location: events.php?error=invalidEmail");
                        } else {
                            
                        $passwordQuery = "SELECT password FROM users WHERE email='$email'";
                        $passwordResult = $dbh->query($passwordQuery);
                        $row2 = $passwordResult->fetch(PDO::FETCH_LAZY);
                        $dbPassword = $row2['password'];
                        
                        if ($password != $dbPassword) {
                            header("Location: events.php?error=invalidPassword");
                        } else {
                        
                        //Quickly collect all other user data for use during their session
                        $userInformation = "SELECT * FROM users WHERE email='$email' AND password='$password'";
                        $userResult = $dbh->query($userInformation);
                        $row3 = $userResult->fetch(PDO::FETCH_LAZY);
                        
                        $_SESSION["id"] = $row3['id'];
                        $_SESSION["membership_type"] = $row3['membership_type'];
                        
                        header("Location: events.php");
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