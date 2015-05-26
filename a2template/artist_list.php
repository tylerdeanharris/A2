<?php
	session_start();
	include("dbconnect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<?php include("inc/header.php"); ?>
    <title>Artists - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?> 
          <section id="main-content">
          	<h1>Featured Artist</h1>
            <?php
				$test = $dbh->query("SELECT MAX(artist_id) AS max_id, MIN(artist_id) AS min_id FROM artistInput");
				$result = $test->fetch(PDO::FETCH_ASSOC);
				a:
				$random = mt_rand($result['min_id'], $result['max_id']);
				$featured = $dbh->query("SELECT * FROM artistInput WHERE artist_id >= $random LIMIT 0,1");
				$featuredResult = $featured->fetch(PDO::FETCH_ASSOC);
				if ($featuredResult['artist_id'] != $random) {
					goto a;
				} else {
					echo '<div>';
					$image_url = $featuredResult['location'];
					$null = "SELECT location FROM artistInput WHERE location = '".$featuredResult['location']."' ";
					$result = $dbh->query($null);
					if($result) {
						echo "<img width='300px' height='200px' src='".$image_url."'>";
					} else{ 
						echo "<img src='Artist_images/default-avatar.jpg'>";
					}
					echo '<a href="artist_description.php?artist_id=', $featuredResult['artist_id'], '&artist_name=', $featuredResult['artist_name'], '"><h2>', $featuredResult['artist_name'], '</h2></a>';
					echo '<p>', $featuredResult['artist_description'], '</p>';
					echo '<p>', $featuredResult['genre'], '</p>';
					echo '<p>', $featuredResult['email'], '</p>';
					echo '</div>';
				}
			?>
          	<h2>Artists</h2>
			<?php
			
			if (isset($_POST['music_genre'])){
				for($i=0; $i < count($_POST['music_genre']); $i++){  
					// count each check box that was clicked.
					$selected_genre = $_POST['music_genre'][$i];
					$result = $dbh->prepare('SELECT * FROM artistInput WHERE music_genre = :music_genre');
					$result->bindValue(':music_genre', $selected_genre);
					$data = $result->execute();
					foreach($result as $row) {
						echo '<section id="artist_list">';
							echo "<ul class='list_format'>";
								echo "<li><div><img width='250px' height='200px' src='" . $row['location'] . "'></div></li>";
								//create a link that sends to artist decription, when the artist name is clicked.
								echo '<li><div><a href="artist_description.php?artist_id=', $row['artist_id'], '&artist_name=', $row['artist_name'], '"><h2>', $row['artist_name'], '</h2></a></div></li>';
								echo '<li><div><p>', $row['music_genre'], '</p><div></li>';
							echo '</ul>';
						echo "</section>";
					}
				}
			}
			if(!isset($_POST['music_genre'])){
    			$sql3 = "SELECT * FROM artistInput";
				foreach($dbh->query($sql3)as $row) {
					echo '<section id="artist_list">';
						echo "<ul class='list_format'>";
							echo "<li><div><img width='250px' height='200px' src='" . $row['location'] . "'></div></li>";
							//create a link that sends to artist decription, when the artist name is clicked.
							echo '<li><div><a href="artist_description.php?artist_id=', $row['artist_id'], '&artist_name=', $row['artist_name'], '"><h2>', $row['artist_name'], '</h2></a></div></li>';
							echo '<li><div><p>', $row['music_genre'], '</p><div></li>';
							echo '</ul>';
					echo "</section>";
				}
			}
		?>
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
								echo "<table><form action='artist_list.php' method='post'><tr>"
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
									echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='artist.php'>Add New Artist</a>)</p><p>(<a href='update_form.php'>Update an Artist</a>)</p><p>(<a href='delete_form.php'>Delete an Artist</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
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
								header("Location: artist_list.php?error=noEmail");
								exit;
							}
							
							//Check if the user provided email is correct
							$emailQuery = "SELECT email FROM users WHERE email='$email'";
							$emailResult = $dbh->query($emailQuery);
							$row = $emailResult->fetch(PDO::FETCH_LAZY);
							$dbEmail = $row['email'];
							   
							if ($email != $dbEmail) {
								header("Location: artist_list.php?error=invalidEmail");
							} else {
								
							$passwordQuery = "SELECT password FROM users WHERE email='$email'";
							$passwordResult = $dbh->query($passwordQuery);
							$row2 = $passwordResult->fetch(PDO::FETCH_LAZY);
							$dbPassword = $row2['password'];
							
							if ($password != $dbPassword) {
								header("Location: artist_list.php?error=invalidPassword");
							} else {
							
							//Quickly collect all other user data for use during their session
							$userInformation = "SELECT * FROM users WHERE email='$email' AND password='$password'";
							$userResult = $dbh->query($userInformation);
							$row3 = $userResult->fetch(PDO::FETCH_LAZY);
							
							$_SESSION["id"] = $row3['id'];
							$_SESSION["membership_type"] = $row3['membership_type'];
							
							header("Location: artist_list.php");
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
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>