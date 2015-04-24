<?php
	include ("dbconnect.php");
	$debugOn = true;  

	
	$sql = "SELECT * FROM artistInput";
		foreach ($dbh->query($sql) as $row){
			echo "<form id='update_form' name='update_form' method='post' action='update_form.php'>"
			."<label>id</label>";
				echo "<input type='text' name='id' value='$row[id]' size='2' />"
			."<label>Artist Name:</label>";
				echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
			."<label>Artist Description:</label>";
				echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
			."<label>Email:</label>";
				echo "<input type='text' name='email' value='$row[email]' />"
			."<label>Genre:</label>";
				echo "<input type='text' name='music_genre' value='$row[music_genre]' />"
			."<label>Password:</label>";
				echo "<input type='text' name='password' value='$row[password]' />";
				echo "<input type='hidden' name='id' value='$row[id]' />";
		
			echo "<input type='submit' name='submit' value='Update Entry' class='update_form'/>"
			."</form>";
		}
			
	  if ($_REQUEST['submit'] == "Update Entry"){
            $sql = "UPDATE artistInput SET artist_name = '$_REQUEST[artist_name]', artist_description = '$_REQUEST[artist_description]', email = '$_REQUEST[email]',  music_genre = '$_REQUEST[music_genre]', password = '$_REQUEST[password]' WHERE id = '$_REQUEST[id]'";
            echo "<p>Query: " . $sql . "</p>\n<p><strong>"; 
            if ($dbh->exec($sql)){
                echo "Updated $_REQUEST[artist_name]";
				echo "<p><a href='artist.php'>Click to add new artist</a></p>"
				."<p><a href='delete_form.php'>Click to delete an exisiting artist</a>";

			}
	  }

?>