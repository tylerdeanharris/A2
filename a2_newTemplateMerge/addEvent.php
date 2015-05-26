<?php
	session_start();
	include("dbconnect.php");
	if (!$_SESSION['id'] || $_SESSION['membership_type'] != 2) {
		header("Location: events.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Add Event Post - Townsville Community Music Centre</title>
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
									} else if ($_REQUEST['error'] == 'noVenue') {
										echo "<p class='loginError'>Please provide the event venue.</p>";
									}
								}
						?>
                        <table>
                            <form method="post" action="addEvent.php" enctype="multipart/form-data">
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
                                    <td>Select Image:</td>
                                    <td><input type="file" name="image" id="image" /></td>
                                </tr>
                                <tr>
                                    <td>Venue:</td>
                                    <td><input name="venue" size="43"/></td>
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
							if(empty($_POST['venue'])) {
								header("Location: addEvent.php?error=noVenue");
								exit();
							}
							
							$title = $_POST['title'];
							$information = $_POST['information'];
							$venue = $_POST['venue'];
							$date_created = date("d.m.y");
							$expire = new DateTime($date_created);
							$expire->modify('+30 day');
							$expire = $expire->format('d-m-Y');
							
							//Only allow certain Images to be uploaded
							if ((($_FILES["image"]["type"] == "image/gif")
							|| ($_FILES["image"]["type"] == "image/jpeg")
							|| ($_FILES["image"]["type"] == "image/png")
							|| ($_FILES["image"]["type"] == "image/pjpeg"))
							&& ($_FILES["image"]["size"] < 2000000)) {
								
								// check for any error code in the data.
								if ($_FILES["image"]["error"] > 0) {
									echo "Error Code: " . $_FILES["image"]["error"] . "<br />";
									echo "<p><a href='addEvent.php'>Return to add event page</a></p>:";
								} else {	
									//store img on server .
									$file = $_FILES['image']['tmp_name'];
									//Checking if photo has alread been stored. 
									if (file_exists("eventPhotos/" . $_FILES["image"]["name"])){
										echo $_FILES["image"]["name"] . " already exists. \n";
									} else {						
										$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
										$image_name = addslashes($_FILES['image']['name']);						
										move_uploaded_file($_FILES["image"]["tmp_name"],"eventPhotos/" . $_FILES["image"]["name"]);
										$location="eventPhotos/" . $_FILES["image"]["name"];
										$sql = "INSERT INTO events (title, information, venue, date_created, date_expires, created_by, location) VALUES ('$title', '$information','$venue','$date_created','$expire','$_SESSION[id]','$location')";
											if ($dbh->exec($sql)) {
												//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
												header("Location: events.php");
											} else {
													echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
											}
										}
									} 
							//if no imgae has been inserted, assign the artist a default image.		
							} else {
								if(empty($_FILES['image']['name']))	{
									$sql = "INSERT INTO events (title, information, venue, date_created, date_expires, created_by, location) VALUES ('$title', '$information','venue','$date_created','$date_expires','$_SESSION[id]','eventPhotos/default.jpg')";
										if ($dbh->exec($sql)){
											//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
											header("Location: events.php");
										} else {
											echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
										}
								//display error message if invalid image has been submitted	
								} else {
									echo "You have entered an invalid Image type. Please enter the following imgae formats: gif, jpeg, png, pjpeg";
								}
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
          <div class="sidebox_sponsor">
          <h1 id="sponsortitle">
          	A special thanks to our sponsors 
          </h1>
                <img src="TCCcolour150193 Small.gif" id="sponosr1"/>
                <img src="QG Small.gif" id="sponosr2"/>
          </div>
          	<?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>