<?php
	session_start();
	include("dbconnect.php");
	$debugOn = true;  
	if (!isset($_SESSION['id'])) {
		header("Location: artist_list.php");
	} else if ($_SESSION['membership_type'] != 1) {
		header("Location: artist_list.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Update Artist - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                    <p>Select artist to update: </p>
                    <form action="update_form.php" method="post">
                    <select name="options">
                    <?php
						$sqL = "SELECT * FROM artistInput";
						foreach($dbh->query($sqL) as $row){
							echo "<option value=".$row['artist_id'].">".$row['artist_name']."</option>";
						}
                   	echo' </select>
                    <input type="submit" name="button"/>
                    </form>';
					  $selected = $_POST['options'];
					  
										//Display whats in the database at the moment
					$sql = "SELECT * FROM artistInput WHERE artist_id=$selected";
						
						foreach ($dbh->query($sql) as $row){
							echo "<form id='update_form' name='update_form' method='post' action='update_form.php' enctype='multipart/form-data'>"
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
								echo "<input type='submit' name='submit' value='Update Entry' class='update_form'/>"
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
							echo "<p><a href='artist.php'>Return to Artist sign up page</a></p>:";
							
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
									$sql = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]', location='artistPhotos/$name' WHERE artist_id=$_POST[artist_id]";
										if ($dbh->exec($sql)){
											echo "$_REQUEST[artist_name] has been updated.";
											//refresh page if when submit button has been selected.
											header("Location: update_form.php");
										}
									}
									} else {
									$sql = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]' WHERE artist_id=$_POST[artist_id]";
										if ($dbh->exec($sql)){
											echo "$_REQUEST[artist_name] has been updated.";
											//refresh page if when submit button has been selected.
											header("Location: update_form.php");
								}
							}
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