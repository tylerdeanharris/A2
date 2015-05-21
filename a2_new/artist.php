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
                        <h1>Artist Database</h1>
                        <form id="insert" name="insert" method="post" enctype="multipart/form-data" action="db_process_artist.php">
                        <fieldset class="subtleSet">
                            <h2>Insert new artist:</h2>
                            <p>
                              <label for="artist_name">Artist Name: </label>
                              <input type="text" name="artist_name" id="artist_name" />
                            </p>
                            <p>
                              <label for="artist_description">Artist Description: </label>
                              <input type="text" name="artist_description" id="artist_description" />
                            </p>
                            <p>
                            <script type="text/javascript">
                            function select_options(music_genre){
                                if(music_genre=='Other')document.getElementById('hidden_div').innerHTML='Other: <input type="text" name="music_genre" />';
                                    else document.getElementById('hidden_div').innerHTML=''};
                            </script>
                            <label>Genre:</label>
                            <select name="music_genre" id="music_genre" onchange="select_options(this.options[this.selectedIndex].value)">
                                <option value="Rock">Rock</option>
                                <option value="Hip-hop">Hip-hop</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Funk">Funk</option>
                                <option value="Folk">Folk</option>
                                <option value="Reggae">Reggae</option>
                                <option value="Pop">Pop</option>
                                <option value="Dance">Dance</option>
                                <option value="Classic">Classic</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="hidden_div"></div>
                            </p>
                            <p>
                              <label for="email">Email: </label>
                              <input type="text" name="email" id="email" />
                            </p>
                            <p>
                            Select Image: <br />
                            <input type="file" name="image" id = "image" /><br />
                            </p>
                            <p>
                              <input type="submit" name="submit" id="submit" value="Insert Entry" />
                            </p>
                        </fieldset>
                        </form>
                        </fieldset>
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