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
    <title>Edit Event - Townsville Community Music Centre</title>
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
								$query = $dbh->query("SELECT * FROM events WHERE id=" . $_REQUEST['id'] . "");
								$result = $query->fetch(PDO::FETCH_ASSOC);
								?>
                                    <table>
                                        <form method="post" action="editEvent.php?id=<?php echo $_REQUEST['id']; ?>">
                                            <tr>
                                                <h1>Edit an Event</h1>
                                                <td>Title:</td>
                                                <td><input type="text" name="title" size="43" value="<?php echo $result['title']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Information:</td>
                                                <td><textarea name="information" rows="10" cols="40"><?php echo $result['information']; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Location:</td>
                                                <td><input name="location" size="43" value="<?php echo $result['location']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Save Event" name="editEvent" /></td>
                                            </tr>
                                        </form>
                                    </table>
                            	<?php
							}
							
							function processForm() {
								//Connect to the database
								include("dbconnect.php");
								
								if(empty($_POST['title'])) {
									header("Location: editEvent.php?error=noTitle");
									exit();
								}
								if(empty($_POST['information'])) {
									header("Location: editEvent.php?error=noInformation");
									exit();
								}
								if(empty($_POST['location'])) {
									header("Location: editEvent.php?error=noLocation");
									exit();
								}
								
								$title = $_POST['title'];
								$information = $_POST['information'];
								$location = $_POST['location'];
								
								//If everything above has passed, update any changes
								$editEvent = "UPDATE events SET title = '$title', information = '$information', location = '$location' WHERE id='$_REQUEST[id]'";
								if ($dbh->exec($editEvent)) {
									echo 'Updated successfully...';
									echo '<br /><a href="events.php">Back to Events</a>';
								} else {
									echo "Sorry, but it appears something went wrong...";
								}
								
							}
							
							if (isset($_POST['editEvent'])) {
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