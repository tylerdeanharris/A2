<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
<?php
	session_start();
	include("dbconnect.php");
	if(!isset($_SESSION['id'])) {
		header("Location: index.php");
	}
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
						
							function displayForm() {
								if (isset($_REQUEST['error'])) {
									if ($_REQUEST['error'] == 'noTitle') {
										echo "<p class='loginError'>Please enter a post title.</p>";
									} else if ($_REQUEST['error'] == 'noContent') {
										echo "<p class='loginError'>Please enter some content for the post.</p>";
									} else if ($_REQUEST['error'] == 'noContact') {
										echo "<p class='loginError'>Please provide some contact details.</p>";
									}
								}
								?>
                                    <table>
                                        <form method="post" action="addBulletin.php">
                                            <tr>
                                                <h1>New Bulletin Post</h1>
                                                <td>Title:</td>
                                                <td><input type="text" name="title" size="43" autofocus="autofocus" /></td>
                                            </tr>
                                            <tr>
                                                <td>Body Content:</td>
                                                <td><textarea name="body" rows="10" cols="40"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Contact Information:</td>
                                                <td><textarea name="contact" rows="5" cols="40"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Create Post" name="addBulletin"</td>
                                            </tr>
                                        </form>
                                    </table>
                            	<?php
							}
							
							function processForm() {
								if(empty($_POST['title'])) {
									header("Location: addBulletin.php?error=noTitle");
									exit();
								}
								if(empty($_POST['body'])) {
									header("Location: addBulletin.php?error=noContent");
									exit();
								}
								if(empty($_POST['contact'])) {
									header("Location: addBulletin.php?error=noContact");
									exit();
								}
								
								$insert = "INSERT INTO bulletin_board (title, body, date_created, date_expires, created_by, contact_preference) VALUES ('$first_name', '$last_name', '$email', '$address', '$phone_day', '$phone_after_hours', '$mobile', '$password')";
								if ($dbh->exec($insert)) {
									echo "Congratulations " . $first_name . "! Your membership registration was procesed successfully.<br><br><a href=login.php>Login</a> | <a href=index.php>Index</a>";
								} else {
									echo "Sorry, but it appears an error has occurred while processing your membership registration. If the problem persists, please contact us for assistance!";
								}
								
							}
							
							if (isset($_POST['addBulletin'])) {
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
						$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
						$result = $dbh->query($getUserInfo);
						$row = $result->fetch(PDO::FETCH_LAZY);
						if ($row['membership_type'] == 0) {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
						} else {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=#>Bulletin Board</a>)</p><p>(<a href=#>Artists</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
						}
					?>
                </div>
            </aside>
            
            <footer>
                <small>&copy; <a href="index.php" title="Townsville Community Music Centre">Townsville Community Music Centre</a>. All rights reserved.</small>
            </footer>
        </div>
	</body>
</html>
<body>
</body>
</html>