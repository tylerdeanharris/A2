<?php
	session_start();
	include("dbconnect.php");
	$debugOn = true;  

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
										//Display whats in the database at the moment
					$sql = "SELECT * FROM artistInput";
					
						echo "<p><a href='artist.php'>Click to add new artist</a></p>"
						."<p><a href='delete_form.php'>Click to delete an exisiting artist</a>";
						
						foreach ($dbh->query($sql) as $row){
							echo "<form id='update_form' name='update_form' method='post' action='update_form.php' enctype'multipart/form-data'>"
							."<label>Artist Name:</label>";
								echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
							."<label>Artist Description:</label>";
								echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
							."<label>Email:</label>";
								echo "<input type='text' name='email' value='$row[email]' />"
							."<label>Genre:</label>";
				?>			
						<script type="text/javascript">
						function select_options(music_genre){
							if(music_genre=='Other')document.getElementById('hidden_div').innerHTML='Other: <input type="text" name="music_genre" />';
								else document.getElementById('hidden_div').innerHTML=''};
						</script>
						
						<select name="music_genre" id="music_genre" onchange="select_options(this.options[this.selectedIndex].value)">
				<?php       echo"<option value=" . $row['music_genre'] . ">". $row['music_genre']."</option>"; ?>
				
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
				<?php		
				
								echo"<label>Image:</label>";
								echo "<p><img width='300px' height='300px' src='".$row['location']."'></p>";
								echo '<input type="file" name="image" id="image"></li>';
								echo "<input type='hidden' name='artist_id' value='$row[artist_id]' />";
								echo "<input type='submit' name='submit' value='Update Entry' class='update_form'/>"
							."</form>";
						}
								
						if ($_REQUEST['submit']) {
							if (empty($_FILES['image']['name'])) {
								//Update text inputs if no image has been update.
								$sql = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]' WHERE artist_id = '$_REQUEST[artist_id]'";
									if ($dbh->exec($sql)){
										echo "$_REQUEST[artist_name] has been updated.";
										//refresh page if when submit button has been selected.
										header("Location: update_form.php");
									}
								} else {
									$sql = "UPDATE artistInput SET location='$location' WHERE artist_id='$_REQUEST[artist_id]'";
									echo "testtt!!";
									if ($dbh->exec($sql)) {
										echo "Updated $_REQUEST[artist_name]";
										echo "YEAHHHH coba";
									} else {
										echo "Not updated";
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