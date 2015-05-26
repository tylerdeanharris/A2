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
    <title><?php echo "" . $row['first_name'] . "'s Profile"; ?> - Townsville Community Music Centre</title>
	<body>
          <div id="main-section">
          	<?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                        <form method="post" action="profile.php">
                        	<h1><?php echo "" . $row['first_name'] . "'s Profile:"; ?></h1>
                        	<table>
                            	<tr>
                                	<td>First Name: </td>
                                    <td><?php echo $row['first_name']; ?></td>
                                </tr>
                                <tr>
                                	<td>Last Name: </td>
                                    <td><?php echo $row['last_name']; ?></td>
                                </tr>
                                <tr>
                                	<td>Email: </td>
                                    <td><?php echo $row['email']; ?></td>
                                </tr>
                                <tr>
                                	<td>Address: </td>
                                    <td><?php echo $row['address']; ?></td>
                                </tr>
                                <tr>
                                	<td>Daytime Number: </td>
                                    <td><?php echo $row['phone_day']; ?></td>
                                </tr>
                                <tr>
                                	<td>After-Hours Number: </td>
                                    <td><?php echo $row['phone_after_hours']; ?></td>
                                </tr>
                                <tr>
                                	<td>Mobile Number: </td>
                                    <td><?php echo "0" . $row['mobile'] . "" ?></td>
                                </tr>
                                <tr>
                                	<td>Membership Type: </td>
                                    <td><?php if($row['membership_type'] == 0) { ?>
                                    </form>
                                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                        <input type="hidden" name="cmd" value="_s-xclick">
                                        <input type="hidden" name="hosted_button_id" value="GCRJ28AFLXURQ">
                                        <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_paynow_SM.gif" name="submit" alt="PayPal � The safer, easier way to pay online.">s
                                        <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                                    </form>
                                    <form>
                                    <?php } else { echo "Paid"; } ?></td>
                                </tr>
                            </table>
                        </form>
                        <br /><a href="editProfile.php?id=<?php echo $_SESSION['id']; ?>">[Edit Profile]</a><a href="deleteProfile.php?id=<?php echo $_SESSION['id']; ?>">[Delete Profile]</a>
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