<?php
	session_start();
	include("dbconnect.php");
	if ($_REQUEST['submit'] == "X"){
		$sql = "DELETE FROM artistInput WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($sql))
		header("Location: artist.php"); // NOTE: This must be done before ANY html is output, which is why it's right at the top!
	}
	if (!isset($_SESSION['id'])) {
		header("Location: artist_list.php");
	} else if ($_SESSION['membership_type'] != 1) {
		header("Location: artist_list.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
	<title>Delete Artist - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                    <?php                        
                    // Display what's in the database at the moment.
                    $sql = "SELECT * FROM artistInput";
                    
                    foreach ($dbh->query($sql) as $row){
                        echo "<form id='deleteForm' name='deleteForm' method='post' action='delete_form.php'/>"
                        ."<label>Artist Name:</label>";
                            echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
                        ."<label>Artist Description:</label>";
                            echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
                        ."<label>Email:</label>";
                            echo "<input type='text' name='email' value='$row[email]' />"
                        ."<label>Genre:</label>";
                            echo "<input type = 'text' name music_genre  value='$row[music_genre]' />";	
                            echo "<label>Image:</label>"
                        .'<p><img width="300px" height="300px" src="'.$row['location'].'"></p>';
                            echo "<input type='hidden' name='artist_id' value='$row[artist_id]' />";
                    
                        echo "<input type='submit' name='submit' value='Delete Artist' class='deleteButton'/>"
                        ."</form>";
                    }
                    if ($_REQUEST['submit'] == "Delete Artist"){
                        $sql = "DELETE FROM artistInput WHERE artist_id = '$_REQUEST[artist_id]'";
                        if ($dbh->exec($sql))
                            echo "<p>Query: " . $sql . "</p>\n<p><strong>";
                            header("Location: delete_form.php");
                    }
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