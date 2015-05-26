<?php
	session_start();
	include("dbconnect.php");
	if (!$_SESSION['id']) {
		header("Location: index.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Edit Profile - Townsville Community Music Centre</title>
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
									if ($_REQUEST['error'] == 'noFirstName') {
										echo "<p class='loginError'>Please provide a first name.</p>";
									} else if ($_REQUEST['error'] == 'noLastName') {
										echo "<p class='loginError'>Please provide a last name.</p>";
									} else if ($_REQUEST['error'] == 'noEmail') {
										echo "<p class='loginError'>Please provide an email address.</p>";
									} else if ($_REQUEST['error'] == 'noAddress') {
										echo "<p class='loginError'>Please provide an address.</p>";
									} else if ($_REQUEST['error'] == 'noDaytimePhone') {
										echo "<p class='loginError'>Please provide a daytime contact number.</p>";
									} else if ($_REQUEST['error'] == 'noAfterHoursPhone') {
										echo "<p class='loginError'>Please provide an after-hours contact number.</p>";
									} else if ($_REQUEST['error'] == 'noMobile') {
										echo "<p class='loginError'>Please provide a mobile number.</p>";
									}
								}
								$query = $dbh->query("SELECT * FROM users WHERE id=" . $_REQUEST['id'] . "");
								$result = $query->fetch(PDO::FETCH_ASSOC);
							?>
                                    <table>
                                        <form method="post" action="editProfile.php?id=<?php echo $_REQUEST['id']; ?>">
                                            <tr>
                                                <h1>Edit Profile</h1>
                                                <td>First Name:</td>
                                                <td><input type="text" name="first_name" size="43" value="<?php echo $result['first_name']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Last Name:</td>
                                                <td><textarea name="last_name" rows="10" cols="40"><?php echo $result['last_name']; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td><input name="email" size="43" value="<?php echo $result['email']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Address:</td>
                                                <td><input name="address" size="43" value="<?php echo $result['address']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Daytime Number:</td>
                                                <td><input name="phone_day" size="43" value="<?php echo $result['phone_day']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>After-Hours Number:</td>
                                                <td><input name="phone_after_hours" size="43" value="<?php echo $result['phone_after_hours']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Mobile Number:</td>
                                                <td><input name="mobile" size="43" value="0<?php echo $result['mobile']; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="submit" value="Save Profile" name="editProfile" /></td>
                                            </tr>
                                        </form>
                                    </table>
                            	<?php
							}
							
							function processForm() {
								//Connect to the database
								include("dbconnect.php");
								
								if(empty($_POST['first_name'])) {
									header("Location: editProfile.php?error=noFirstName");
									exit();
								}
								if(empty($_POST['last_name'])) {
									header("Location: editProfile.php?error=noLastName");
									exit();
								}
								if(empty($_POST['email'])) {
									header("Location: editProfile.php?error=noEmail");
									exit();
								}
								if(empty($_POST['address'])) {
									header("Location: editProfile.php?error=noAddress");
									exit();
								}
								if(empty($_POST['phone_day'])) {
									header("Location: editProfile.php?error=noDaytimePhone");
									exit();
								}
								if(empty($_POST['phone_after_hours'])) {
									header("Location: editProfile.php?error=noAfterHoursPhone");
									exit();
								}
								if(empty($_POST['mobile'])) {
									header("Location: editProfile.php?error=noMobile");
									exit();
								}
								
								//If everything above has passed, update any changes
								$editProfile = "UPDATE users SET first_name = '$_POST[first_name]', last_name = '$_POST[last_name]', email = '$_POST[email]', address = '$_POST[address]', phone_day = '$_POST[phone_day]', phone_after_hours = '$_POST[phone_after_hours]', mobile = '$_POST[mobile]' WHERE id='$_REQUEST[id]'";
								if ($dbh->exec($editProfile)) {
									echo 'Updated successfully...';
									echo '<br /><a href="profile.php">Back to you Profile</a>';
								} else {
									echo "Sorry, but it appears something went wrong...";
								}
								
							}
							
							if (isset($_POST['editProfile'])) {
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