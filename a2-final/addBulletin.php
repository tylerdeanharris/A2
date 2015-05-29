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
                                        <form method="post" action="addBulletin.php" enctype="multipart/form-data">
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
                                            	<td>Select Image:</td>
                            					<td><input type="file" name="image" id="image" /></td>
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
								
								//Only allow certain Images to be uploaded
								if ((($_FILES["image"]["type"] == "image/gif")
								|| ($_FILES["image"]["type"] == "image/jpeg")
								|| ($_FILES["image"]["type"] == "image/png")
								|| ($_FILES["image"]["type"] == "image/pjpeg"))
								&& ($_FILES["image"]["size"] < 2000000)) {
									
									// check for any error code in the data.
									if ($_FILES["image"]["error"] > 0) {
										echo "Error Code: " . $_FILES["image"]["error"] . "<br />";
										echo "<p><a href='addBulletin.php'>Return to add bulletin page</a></p>:";
									} else {	
										//store img on server .
										$file = $_FILES['image']['tmp_name'];
										//Checking if photo has alread been stored. 
										if (file_exists("bulletinPhotos/" . $_FILES["image"]["name"])){
											echo $_FILES["image"]["name"] . " already exists. \n";
										} else {						
											$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
											$image_name = addslashes($_FILES['image']['name']);						
											move_uploaded_file($_FILES["image"]["tmp_name"],"bulletinPhotos/" . $_FILES["image"]["name"]);
											$location="bulletinPhotos/" . $_FILES["image"]["name"];
											$sql = "INSERT INTO bulletin_board (title, body, date_created, date_expires, created_by, contact_preference, location) VALUES ('$title', '$body','$date_created','$expire','$_SESSION[id]','$contact_preference','$location')";
												if ($dbh->exec($sql)) {
													//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
													header("Location: bulletin.php");
												} else {
														echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
												}
											}
										} 
								//if no imgae has been inserted, assign the artist a default image.		
								} else {
									if(empty($_FILES['image']['name']))	{
										$sql = "INSERT INTO bulletin_board (title, body, date_created, date_expires, created_by, contact_preference, location) VALUES ('$title', '$body','date_created','$expire','$_SESSION[id]','$contact_preference','bulletinPhotos/default.jpg')";
											if ($dbh->exec($sql)){
												//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
												header("Location: bulletin.php");
											} else {
												echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
											}
									//display error message if invalid image has been submitted	
									} else {
										echo "You have entered an invalid Image type. Please enter the following imgae formats: gif, jpeg, png, pjpeg";
									}
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
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
						} else if ($row['membership_type'] == 1) {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
						} else {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href='adminPanel.php'>Control Panel</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
						}
					?>
                </div>
            </aside>
            <?php include("inc/footer.php"); ?>
            </div>
          </aside>
          <?php include("inc/sponsor.php"); ?>
          <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>