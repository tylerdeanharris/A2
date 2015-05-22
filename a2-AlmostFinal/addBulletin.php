<?php
	session_start();
	include("dbconnect.php");
	if (!$_SESSION['id']) {
		header("Location: bulletin.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Add Bulletin Post - Townsville Community Music Centre</title>
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
                                                <td><input name="contact" size="43"/></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Create Post" name="addBulletin" /></td>
                                            </tr>
                                        </form>
                                    </table>
                            	<?php
							}
							
							function processForm() {
								//Connect to the database
								include("dbconnect.php");
								
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
								
								$title = $_POST['title'];
								$body = $_POST['body'];
								$date_created = date("d.m.y");
								$expire = new DateTime($date_created);
								$expire->modify('+30 day');
								$expire = $expire->format('d.m.Y');
								$contact_preference = $_POST['contact'];
								
								//If everything above has passed, create the post
								$insertPost = "INSERT INTO bulletin_board (title,body,date_created,date_expires,created_by,contact_preference) VALUES ('$title','$body','$date_created','$expire','$_SESSION[id]','$contact_preference')";
								if ($dbh->exec($insertPost)) {
									header("Location: bulletin.php");
								} else {
									echo "Sorry, but it appears something went wrong...";
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
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
						} else {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href=#>Profile</a>)</p><p>(<a href=#>Bulletin Board</a>)</p><p>(<a href=#>Artists</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
						}
					?>
                </div>
            </aside>
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>
<body>
</body>
</html>