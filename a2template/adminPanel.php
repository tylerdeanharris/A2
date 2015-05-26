<?php
	session_start();
	include("dbconnect.php");
	$debugOn = true;  
	if ($_SESSION['membership_type'] != 2) {
		header("Location: index.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
	<title>Administration Panel- Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                        <h1>Members:</h1>
                        <p>Select Member Profile to Edit: </p>
                        <form action="adminPanel.php" method="POST">
                        <select name="memberOptions">
                        <?php
                            $editMemberQuery = "SELECT * FROM users";
                            foreach($dbh->query($editMemberQuery) as $row){
                                echo "<option value=".$row['id'].">".$row['first_name']. " " .$row['last_name']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['memberOptions'];
                          
                            //Display whats in the database at the moment
                            $editMemberQuery = $dbh->query("SELECT * FROM users WHERE id=$selected");
                            
                            foreach ($editMemberQuery as $row){
                                echo "<form id='member_update' name='member_update' method='POST' action='adminPanel.php' enctype='multipart/form-data'>"
                                ."<label>Member First Name:</label>";
                                    echo "<input type='text' name='first_name' value='$row[first_name]' />"
                                ."<label>Member Last Name:</label>";
                                    echo "<input type='text' name='last_name' value='$row[last_name]' />"
                                ."<label>Email:</label>";
                                    echo "<input type='text' name='email' value='$row[email]' />"
								."<label>Address:</label>";
                                    echo "<input type='text' name='address' value='$row[address]' />"
								."<label>Daytime Phone Number:</label>";
                                    echo "<input type='number' name='phone_day' value='$row[phone_day]' />"
								."<label>After Hours Phone Number:</label>";
                                    echo "<input type='number' name='phone_after_hours' value='$row[phone_after_hours]' />"
								."<label>Mobile Number:</label>";
                                    echo "<input type='number' name='mobile' value='$row[mobile]' />"
								."<label>Membership Type:</label>";
                                    echo "<input type='number' name='membership_type' value='$row[membership_type]' />"
                                ."<input type='hidden' name='id' value='$row[id]' />";
                                    echo "<input type='submit' name='submit' value='Update Entry' class='member_update'/>"
                                ."</form>";
                            }
                            
                                $editMemberQuery = "UPDATE users SET first_name = '$_REQUEST[first_name]', last_name = '$_REQUEST[last_name]', email = '$_REQUEST[email]', address = '$_REQUEST[address]', phone_day = '$_REQUEST[phone_day]', phone_after_hours = '$_REQUEST[phone_after_hours]', mobile = '$_REQUEST[mobile]', membership_type = '$_REQUEST[membership_type]' WHERE id=$_POST[id]";
                                if ($dbh->exec($editMemberQuery)){
                                    echo "$_REQUEST[first_name] has been updated.";
                                    //refresh page if when submit button has been selected.
                                    header("Location: adminPanel.php");
                                }		
                            ?>
                            <p>Select Member Profile to Delete:</p>
                            <form action="adminPanel.php" method="POST">
                            <select name="profileDeleteOptions">
                            <?php
                                $deleteProfileQuery = "SELECT * FROM users";
                                foreach($dbh->query($deleteProfileQuery) as $row){
                                    echo "<option value=".$row['id'].">".$row['first_name']. " " .$row['last_name']."</option>";
                                }
                                
                                echo' </select>
                                <input type="submit" name="button"/>
                                </form>';
                                $selected = $_POST['profileDeleteOptions'];
                              
                                //Display whats in the database at the moment
                                $deleteProfileQuery = "DELETE FROM users WHERE id = '$selected'";
                                if ($dbh->exec($deleteProfileQuery)) {
                                    header("Location: adminPanel.php");
                                } else {
                                    //echo "Error while deleting bulletin post...";
                                }
                            ?>
                    </div>
                    <div>
                        <h1>Bulletin Board:</h1>
                        <p>Select Bulletin Post to Edit:</p>
                        <form action="adminPanel.php" method="POST">
                        <select name="bulletinOptions">
                        <?php
                            $editBulletinQuery = "SELECT * FROM bulletin_board";
                            foreach($dbh->query($editBulletinQuery) as $row){
                                echo "<option value=".$row['id'].">".$row['title']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['bulletinOptions'];
                          
                            //Display whats in the database at the moment
                            $editBulletinQuery = $dbh->query("SELECT * FROM bulletin_board WHERE id=$selected");
                            
                            foreach ($editBulletinQuery as $row){
                                echo "<form id='bulletin_update' name='bulletin_update' method='POST' action='adminPanel.php' enctype='multipart/form-data'>"
                                ."<label>Post Title:</label>";
                                    echo "<input type='text' name='title' value='$row[title]' />"
                                ."<label>Post Body:</label>";
                                    echo "<input type='text' name='body' value='$row[body]' />"
                                ."<label>Contact Preference:</label>";
                                    echo "<input type='text' name='contact_preference' value='$row[contact_preference]' />"
									?>
                                    <?php
									echo"<label>Image:</label>";
									echo "<p><img width='300px' height='300px' src='".$row['location']."'></p>";
									echo '<input type="file" name="image" id="image">';
                                 	echo "<input type='hidden' name='id' value='$row[id]' />";
                                    echo "<input type='submit' name='submit1' value='Update Entry' class='bulletin_update'/>"
                                ."</form>";
                            }
							
							if (isset($_POST['submit1'])) {
							//Only allow certain Images to be uploaded
							if ((($_FILES["image"]["type"] == "image/gif")
							|| ($_FILES["image"]["type"] == "image/jpeg")
							|| ($_FILES["image"]["type"] == "image/png")
							|| ($_FILES["image"]["type"] == "image/pjpeg"))
							&& ($_FILES["image"]["size"] < 2000000)) {
								
							// check for any error code in the data.
							if ($_FILES["image"]["error"] > 0) {
								echo "Error Code: " . $_FILES["image"]["error"] . "<br />";
								echo "<p><a href='adminPanel.php'>Return to the admin panel</a></p>:";
								
							} else {	
							$name = $_FILES["image"]["name"];
		
							$tmp_name = $_FILES['image']['tmp_name'];
							$error = $_FILES['image']['error'];
							}
							if (isset ($name)) {
							if (!empty($name)) {
		
							$location = dirname(__FILE__). '/bulletinPhotos/';
							if  (move_uploaded_file($tmp_name, $location.$name)){
							echo 'Uploaded';
							}
							} else {
							echo 'please choose a file';
							}
								}
									if(!empty($name)){
										$editBulletinQuery = "UPDATE bulletin_board SET title = '$_REQUEST[title]', body = '$_REQUEST[body]', contact_preference = '$_REQUEST[contact_preference]', location='bulletinPhotos/$name' WHERE id=$_POST[id]";
											if ($dbh->exec($editBulletinQuery)){
												echo "$_REQUEST[title] has been updated.";
												//refresh page if when submit button has been selected.
												header("Location: adminPanel.php");
											}
										}
										} else {
										$editBulletinQuery = "UPDATE bulletin_board SET title = '$_REQUEST[title]', body = '$_REQUEST[body]', contact_preference = '$_REQUEST[contact_preference]' WHERE id=$_POST[id]";
											if ($dbh->exec($editBulletinQuery)){
												echo "$_REQUEST[title] has been updated.";
												//refresh page if when submit button has been selected.
												header("Location: adminPanel.php");
									}
								}
							}		
                        ?>
                        <p>Select Bulletin Post to Delete:</p>
                        <form action="adminPanel.php" method="POST">
                        <select name="bulletinDeleteOptions">
                        <?php
							$deleteBulletinQuery = "SELECT * FROM bulletin_board";
                            foreach($dbh->query($deleteBulletinQuery) as $row){
                                echo "<option value=".$row['id'].">".$row['title']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['bulletinDeleteOptions'];
                          
                            //Display whats in the database at the moment
                            $deleteBulletinQuery = "DELETE FROM bulletin_board WHERE id = '$selected'";
							if ($dbh->exec($deleteBulletinQuery)) {
								header("Location: adminPanel.php");
							} else {
								//echo "Error while deleting bulletin post...";
							}
						?>
                    </div>
                    <div>
                        <h1>Events:</h1>
                        <p>Select Event to Edit:</p>
                        <form action="adminPanel.php" method="POST">
                        <select name="eventOptions">
                        <?php
                            $editEventQuery = "SELECT * FROM events";
                            foreach($dbh->query($editEventQuery) as $row){
                                echo "<option value=".$row['id'].">".$row['title']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['eventOptions'];
                          
                            //Display whats in the database at the moment
                            $editEventQuery = $dbh->query("SELECT * FROM events WHERE id=$selected");
                            
                            foreach ($editEventQuery as $row){
                                echo "<form id='event_update' name='event_update' method='POST' action='adminPanel.php' enctype='multipart/form-data'>"
                                ."<label>Event Title:</label>";
                                    echo "<input type='text' name='title' value='$row[title]' />"
                                ."<label>Event Information:</label>";
                                    echo "<input type='text' name='information' value='$row[information]' />"
                                ."<label>Venue:</label>";
                                    echo "<input type='text' name='venue' value='$row[venue]' />"
									?>
                                    <?php
									echo"<label>Image:</label>";
									echo "<p><img width='300px' height='300px' src='".$row['location']."'></p>";
									echo '<input type="file" name="image" id="image">';
                                 	echo "<input type='hidden' name='id' value='$row[id]' />";
                                    echo "<input type='submit' name='submit' value='Update Entry' class='event_update'/>"
                                ."</form>";
                            }
							
							if (isset($_POST['submit'])) {
							//Only allow certain Images to be uploaded
							if ((($_FILES["image"]["type"] == "image/gif")
							|| ($_FILES["image"]["type"] == "image/jpeg")
							|| ($_FILES["image"]["type"] == "image/png")
							|| ($_FILES["image"]["type"] == "image/pjpeg"))
							&& ($_FILES["image"]["size"] < 2000000)) {
								
							// check for any error code in the data.
							if ($_FILES["image"]["error"] > 0) {
								echo "Error Code: " . $_FILES["image"]["error"] . "<br />";
								echo "<p><a href='adminPanel.php'>Return to the admin panel</a></p>:";
								
							} else {	
							$name = $_FILES["image"]["name"];
		
							$tmp_name = $_FILES['image']['tmp_name'];
							$error = $_FILES['image']['error'];
							}
							if (isset ($name)) {
							if (!empty($name)) {
		
							$location1 = dirname(__FILE__). '/eventPhotos/';
							if  (move_uploaded_file($tmp_name, $location1.$name)){
							echo 'Uploaded';
							}
							} else {
							echo 'please choose a file';
							}
								}
									if(!empty($name)){
										$editEventQuery = "UPDATE events SET title = '$_REQUEST[title]', information = '$_REQUEST[information]', venue = '$_REQUEST[venue]', location='eventPhotos/$name' WHERE id=$_POST[id]";
											if ($dbh->exec($editEventQuery)){
												echo "$_REQUEST[title] has been updated.";
												//refresh page if when submit button has been selected.
												header("Location: adminPanel.php");
											}
										}
										} else {
										$editEventQuery = "UPDATE events SET title = '$_REQUEST[title]', information = '$_REQUEST[information]', venue = '$_REQUEST[venue]' WHERE id=$_POST[id]";
											if ($dbh->exec($editEventQuery)){
												echo "$_REQUEST[title] has been updated.";
												//refresh page if when submit button has been selected.
												header("Location: adminPanel.php");
									}
								}
							}	
                        ?>
                        <p>Select Event to Delete:</p>
                        <form action="adminPanel.php" method="POST">
                        <select name="eventDeleteOptions">
                        <?php
							$deleteEventQuery = "SELECT * FROM events";
                            foreach($dbh->query($deleteEventQuery) as $row){
                                echo "<option value=".$row['id'].">".$row['title']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['eventDeleteOptions'];
                          
                            //Display whats in the database at the moment
                            $deleteEventQuery = "DELETE FROM events WHERE id = '$selected'";
							if ($dbh->exec($deleteEventQuery)) {
								header("Location: adminPanel.php");
							} else {
								//echo "Error while deleting event...";
							}
						?>
                    </div>
                    <div>
                    	<h1>Artists:</h1>
                    	<p>Select Artist to Edit: </p>
                        <form action="adminPanel.php" method="post">
                        <select name="artistOptions">
                        <?php
                            $editArtistQuery = "SELECT * FROM artistInput";
                            foreach($dbh->query($editArtistQuery) as $row){
                                echo "<option value=".$row['artist_id'].">".$row['artist_name']."</option>";
                            }
                        echo' </select>
                        <input type="submit" name="button"/>
                        </form>';
                          	$selected = $_POST['artistOptions'];
                          
                        	//Display whats in the database at the moment
                        	$editArtistQuery = "SELECT * FROM artistInput WHERE artist_id=$selected";
                            
                            foreach ($dbh->query($editArtistQuery) as $row){
                                echo "<form id='artist_form' name='artist_form' method='post' action='adminPanel.php' enctype='multipart/form-data'>"
                                ."<label>Artist Name:</label>";
                                    echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
                                ."<label>Artist Description:</label>";
                                    echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
                                ."<label>Email:</label>";
                                    echo "<input type='text' name='email' value='$row[email]' />"
                                ."<label>Genre:</label>";
                        ?>			
                            <script type="text/javascript">
                            function select_options(music_genre){
                                if(music_genre=='Other')document.getElementById('hidden_div').innerHTML='Other: <input type="text" name="music_genre" />';
                                    else document.getElementById('hidden_div').innerHTML=''};
                            </script>
                            
                            <select name="music_genre" id="music_genre" onchange="select_options(this.options[this.selectedIndex].value)">
                    <?php       echo"<option value=" . $row['music_genre'] . ">". $row['music_genre']."</option>"; ?>
                    
                                <option value="Rock">Rock</option>
                                <option value="Hip-hop">Hip-hop</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Funk">Funk</option>
                                <option value="Folk">Folk</option>
                                <option value="Reggae">Reggae</option>
                                <option value="Pop">Pop</option>
                                <option value="Dance">Dance</option>
                                <option value="Classic">Classic</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="hidden_div"></div>
                    <?php		
                    
                                    echo"<label>Image:</label>";
                                    echo "<p><img width='300px' height='300px' src='".$row['location']."'></p>";
                                    echo '<input type="file" name="image" id="image">';
                                    echo "<input type='hidden' name='artist_id' value='$row[artist_id]' />";
                                    echo "<input type='submit' name='submit2' value='Update Entry' class='update_form'/>"
                                ."</form>";
                            }
                                
                            if (isset($_POST['submit2'])) {
                            //Only allow certain Images to be uploaded
                            if ((($_FILES["image"]["type"] == "image/gif")
                            || ($_FILES["image"]["type"] == "image/jpeg")
                            || ($_FILES["image"]["type"] == "image/png")
                            || ($_FILES["image"]["type"] == "image/pjpeg"))
                            && ($_FILES["image"]["size"] < 2000000)) {
                                
                            // check for any error code in the data.
                            if ($_FILES["image"]["error"] > 0) {
                                echo "Error Code: " . $_FILES["image"]["error"] . "<br />";
                                echo "<p><a href='adminPanel.php'>Return to the admin panel</a></p>:";
                                
                            } else {	
                            $name = $_FILES["image"]["name"];
        
                            $tmp_name = $_FILES['image']['tmp_name'];
                            $error = $_FILES['image']['error'];
                            }
                            if (isset ($name)) {
                            if (!empty($name)) {
        
                            $location = dirname(__FILE__). '/artistPhotos/';
                            if  (move_uploaded_file($tmp_name, $location.$name)){
                            echo 'Uploaded';
                            }
                            } else {
                            echo 'please choose a file';
                            }
                                }
                                    if(!empty($name)){
                                        $editArtistQuery = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]', location='artistPhotos/$name' WHERE artist_id=$_POST[artist_id]";
                                            if ($dbh->exec($editArtistQuery)){
                                                echo "$_REQUEST[artist_name] has been updated.";
                                                //refresh page if when submit button has been selected.
                                                header("Location: adminPanel.php");
                                            }
                                        }
                                        } else {
                                        $editArtistQuery = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]' WHERE artist_id=$_POST[artist_id]";
                                            if ($dbh->exec($editArtistQuery)){
                                                echo "$_REQUEST[artist_name] has been updated.";
                                                //refresh page if when submit button has been selected.
                                                header("Location: adminPanel.php");
                                    }
                                }
                            }
                        ?>
                        <p>Select Artist to Delete:</p>
                        <form action="adminPanel.php" method="POST">
                        <select name="artistDeleteOptions">
                        <?php
                            $deleteArtistQuery = "SELECT * FROM artistInput";
                            foreach($dbh->query($deleteArtistQuery) as $row){
                                echo "<option value=".$row['artist_id'].">".$row['artist_name']."</option>";
                            }
                            
                            echo' </select>
                            <input type="submit" name="button"/>
                            </form>';
                            $selected = $_POST['artistDeleteOptions'];
                          
                            //Display whats in the database at the moment
                            $deleteArtistQuery = "DELETE FROM artistInput WHERE artist_id = '$selected'";
                            if ($dbh->exec($deleteArtistQuery)) {
                                header("Location: adminPanel.php");
                            } else {
                                //echo "Error while deleting artist...";
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
						echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='adminPanel.php'>Control Panel</a>)</p><p>(<a href=logout.php>Logout</a>)</p>";
					?>
                </div>
            </aside>
            <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>