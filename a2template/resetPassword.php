<?php
	session_start();
	include("dbconnect.php");
	if (!$_SESSION['id']) {
		header("Location: index.php");
	}
	$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
	$result = $dbh->query($getUserInfo);
	$row = $result->fetch(PDO::FETCH_LAZY);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Password Reset - Townsville Community Music Centre</title>
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
                                    if ($_REQUEST['error'] == 'emptyFields') {
                                        echo "<p class='loginError'>It appears you may have left a required field(s) empty.</p>";
                                    } else if ($_REQUEST['error'] == 'invalidCurrentPassword') {
                                        echo "<p class='loginError'>Sorry, but it appears your current password did not match our records.</p>";
                                    }else if ($_REQUEST['error'] == 'nonMatchingPasswords') {
                                        echo "<p class='loginError'>Sorry, but it appears your new passwords did not match.</p>";
                                    }
									
                                }
                        ?>
                        <form method="post" action="resetPassword.php?id=<?php echo $_REQUEST['id']; ?>">
                        	<h1>Password Reset:</h1>
                        	<table>
                            	<tr>
                                	<td>Current Password: </td>
                                    <td><input type="password" name="current_password" /></td>
                                </tr>
                            </table>
                            <br />
                            <table>	
                                <tr>
                                	<td>New Password: </td>
                                    <td><input type="password" name="new_password" /></td>
                                </tr>
                                <tr>
                                	<td>Confirm Password: </td>
                                    <td><input type="password" name="confirm_new_password" /></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" value="Reset" name="resetPassword" /></td>
                                </tr>
                            </table>
                        </form>
                        <?php
							}
							function processForm() {
								//Connect to the database
								include("dbconnect.php");
								
								if(empty($_POST['current_password'])) {
									header("Location: resetPassword.php?error=emptyFields");
									exit();
								}
								if(empty($_POST['new_password'])) {
									header("Location: resetPassword.php?error=emptyFields");
									exit();
								}
								if(empty($_POST['confirm_new_password'])) {
									header("Location: resetPassword.php?error=emptyFields");
									exit();
								}
								
								$currentPassword = md5($_POST['currentPassword']);
								$newPassword = $_POST['new_password'];
								$confirmNewPassword = $_POST['confirm_new_password'];
								
								// Make sure the password they entered as their "current password" matches what's stored in the DB
								$passwordQuery = $dbh->query("SELECT password FROM users WHERE id=" . $_REQUEST['id'] . "");
								$passwordResult = $passwordQuery->fetch(PDO::FETCH_ASSOC);
								if ($currentPassword != $result['password']) {
									header("Location: resetPassword.php?error=invalidCurrentPassword");
									exit();
								} else if ($newPassword != $confirmNewPassword) {
									header("Location: resetPassword.php?error=nonMatchingPasswords");
									exit();
								} else {
									$confirmNewPassword = md5($confirmNewPassword);
									$resetPassword = "UPDATE users SET password = '$confirmNewPassword' WHERE id=" . $_REQUEST['id'] . "";
									if ($dbh->exec($resetPassword)) {
										echo 'Password has been reset. Please login to continue...';
										echo '<br /><a href="logout.php">Login</a>';
									} else {
										echo "Sorry, but it appears something went wrong while resetting your password. If the problem persists, please contact us <a href='contact.php'>here</a>.";
									}
								}
							}
							
							if (isset($_POST['resetPassword'])) {
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
						if ($row['membership_type'] == 0) {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
						} else {
							echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=#>Bulletin Board</a>)</p><p>(<a href=#>Artists</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
						}
					?>
                </div>
            </aside>
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>