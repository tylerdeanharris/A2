<?php
	session_start();
	include("dbconnect.php");
	if ($_SESSION['membership_type'] != 2) {
		header("Location: index.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <script type="text/javascript">
		function updateText() {	
			var dd = document.getElementById("book_id");
			var ddtext = dd.options[dd.selectedIndex].text;
			var sp_ddtext = ddtext.replace(/[^ a-zA-Z]+/g,'');
			document.getElementById("book_name").value = sp_ddtext;
		}
	</script>
    <title>Administration Panel - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                        <h1>Artists</h1>
                        <ul>
                        	<li>Add Artist</li>
                            <li>Edit Artist</li>
                            <li>Delete Artist</li>
                        </ul>
                    </div>
                    <div>
                    	<h1>Bulletin Board</h1>
                        <ul>
                        	<li>Add Bulletin Post</li>
                            <li>Edit Bulletin Post</li>
                            <li>Delete Bulletin Post</li>
                        </ul>
                    </div>
                    <div>
                    	<h1>Events</h1>
                        <ul>
                        	<li>Add Event</li>
                            <li>Edit Event</li>
                            <li>Delete Event</li>
                        </ul>
                    </div>
                    <div>
                    	<h1>Members</h1>
                        <ul>
                        	<li>Add Member</li>
                            <form>
                            	<table>
                                	<tr>
                                    	<td><label for="memberName">Member Name: <select name="memberName">
											<?php
                                                $test = $dbh->query("SELECT * FROM users");
                                                foreach($test as $name) {
                                                    echo '<option value="$name[id]">'.$name['first_name'].' '.$name['last_name'].'</option>';
                                                }
                                            ?>
                            </select></label></td></tr></table>
                            </form>
                            <li>Edit Member</li>
                            <li>Delete Member</li>
                        </ul>
                    </div>
                </article>
            </section>
            
            <aside>
                <div class="side-box">
                	<?php
						$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
						$result = $dbh->query($getUserInfo);
						$row = $result->fetch(PDO::FETCH_LAZY);
						echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='adminPanel.php'>Control Panel</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
					?>
                </div>
            </aside>
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>