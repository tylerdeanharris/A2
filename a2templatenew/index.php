<?php
	session_start();
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
            
                <h1 class="logo"><a href="index.php" title="Home">Hom:#</a></h1>
           		<h1 class="heading">Page title here</h1>
                <nav class="primary">
                    <ul>
                        <li><a href="index.php">Nav link</a></li>
                        <li><a href="bulletin.php">Nav link</a></li>
                        <li><a href="#">Nav link</a></li>
                        <li><a href="#">Nav link</a></li>
                        <li><a href="#">Nav link</a></li>
                    </ul>
                </nav>
                
            </header>
          <section id="main-content">
          <article2>
            <div class"genre">
                        <a href="#" title=""><h1>Genre selector</h1></a>
                        <p>selectors here</p>
                        <a href="#" title="Read More">Read More</a>
                    </div>
               </article2>
                 
            <article>
                    <img class="thumbnail" src="#" alt="thumbnail" />
                    <div>
                        <a href="#" title=""><h1>Music Centre</h1></a>
                        <p>Based in Townsville, North Qld, the Music Centre presents concerts and workshops throughout the year, in a diverse range of genres including classical, jazz, folk, blues, world and contemporary music, featuring touring artists and locally-based professional and emerging artists.</p>
                        <a href="#" title="Read More">Read More</a>
                    </div>
                </article>
                <article>
                    <img class="thumbnail" src="#" alt="thumbnail" />
                    <div>
                        <a href="#" title=""><h1>Past 30 Years (you can change this if u want)</h1></a>
                        <p>t has been constantly changing over the years to keep up to date with the musical tastes and needs of the Townsville community. As part of the relocation of the Music Centre to the Civic Theatre, Bronia Renison and Jean Dartnall, both librarians, have assessed the old collection of sheet music, books and recorded music which the centre has been storing, unused, for many years. Sometimes older things have to be discarded to make way for the new, but the Music Centre is aware that older material may still have value. 
The National Library of Australia has an online catalogue (TROVE) that lists not only its own holdings but also information about items held by many other libraries around Australia. Using this catalogue Bronia and Jean have identified at least 150 items of music that are not held by any of the country's major libraries. These items have been donated to the National Library to include in their collection and thus made available to all historians and musicians.

</p>
                        <p>Also discovered in the old collection were some pieces relevant to North Queensland. Local musicians performed these at a musical social afternoon on Sunday April 21st in C2 at the Civic Theatre. The remaining sheet music, books and CDs were put on display and distributed free of charge to the local music community..</p>
                        <a href="#" title="Read More">Read More</a>
                    </div>
                </article>
            
                <article>
                  <div>
                    <a href="#" title=""><h1>Contact</h1></a>
                        <p>Phone: 07 4724 2086</p>
                        <p>Mobile:  0402 255 182</p>
                        <p>Postal Adress: PO Box 1006, Townsville, Qld 4810</p>
                        <p>Address: Townsville Civic Theatre, 41 Boundary Street, Townsville, Qld 4810</p>
                        <a href="#" title="Read More">Read More</a>
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
                
              
          <div class="sidebox_sponsor">
          <h1 id="sponsortitle">
          	A special thanks to our sponsors 
          </h1>
                <img src="TCCcolour150193 Small.gif" id="sponosr1"/>
                <img src="QG Small.gif" id="sponosr2"/>
          </div>
                
            
            
            <footer>
                <small>&copy; <a href="index.php" title="Townsville Community Music Centre">Townsville Community Music Centre</a>. All rights reserved.</small>
            </footer>
        </div>
	</body>
</html>