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
    <title>Edit Bulletin Post - Townsville Community Music Centre</title>
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
								$query = $dbh->query("SELECT * FROM bulletin_board WHERE id=" . $_REQUEST['id'] . "");
								$result = $query->fetch(PDO::FETCH_ASSOC);
								?>
                                    <table>
                                        <form method="post" action="editBulletin.php?id=<?php echo $_REQUEST['id']; ?>" enctype="multipart/form-data">
                                            <tr>
                                                <h1>Edit Bulletin Post</h1>
                                                <td>Title:</td>
                                                <td><input type="text" name="title" size="43" value="<?php echo $result['title']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Body Content:</td>
                                                <td><textarea name="body" rows="10" cols="40"><?php echo $result['body']; ?></textarea></td>
                                            </tr>
                                            <tr>
                                            	<td>Select Image:</td>
                            					<td><input type="file" name="image" id="image" /></td>
                                            </tr>
                                            <tr>
                                                <td>Contact Information:</td>
                                                <td><input name="contact" size="43" value="<?php echo $result['contact_preference']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Save Post" name="editBulletin" /></td>
                                            </tr>
                                        </form>
                                    </table>
                            	<?php
							}
							
							function processForm() {
								//Connect to the database
								include("dbconnect.php");
								
								if(empty($_POST['title'])) {
									header("Location: editBulletin.php?error=noTitle");
									exit();
								}
								if(empty($_POST['body'])) {
									header("Location: editBulletin.php?error=noContent");
									exit();
								}
								if(empty($_POST['contact'])) {
									header("Location: editBulletin.php?error=noContact");
									exit();
								}
								
								$title = $_POST['title'];
								$body = $_POST['body'];
								$contact_preference = $_POST['contact'];
								
								$name = $_FILES["image"]["name"];
								$tmp_name = $_FILES['image']['tmp_name'];
								$error = $_FILES['image']['error'];
			
								if (isset ($name)) {
								if (!empty($name)) {
			
								$location = dirname(__FILE__). '/bulletinPhotos/';
								if  (move_uploaded_file($tmp_name, $location.$name)){
									echo 'Congrats, image uploaded';
								}
			
								} else {
									echo 'Error';
								}
									}
									
										if(empty($name)){
											$sql = "UPDATE bulletin_board SET title = '$title', body = '$body', contact_preference = '$contact_preference' WHERE id='$_REQUEST[id]'";
												if ($dbh->exec($sql)){
													echo "Updated.";
													//refresh page if when submit button has been selected.
													header("Location: bulletin.php");
												}
												
											} else {
													
											$sql = "UPDATE bulletin_board SET title = '$title', body = '$body', contact_preference = '$contact_preference', location='bulletinPhotos/$name' WHERE id='$_REQUEST[id]'";
												if ($dbh->exec($sql)){
													echo "Updated.";
													//refresh page if when submit button has been selected.
													header("Location: bulletin.php");
												}
											}
								
							}
							
							if (isset($_POST['editBulletin'])) {
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