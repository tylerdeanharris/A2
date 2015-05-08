<?php
	include ("dbconnect.php");
	$debugOn = true;
	if ($_REQUEST['submit'] == "X"){
		$sql = "DELETE FROM artistInput WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($sql))
		header("Location: artist.php"); // NOTE: This must be done before ANY html is output, which is why it's right at the top!
	}
	
	// Display what's in the database at the moment.
	$sql = "SELECT * FROM artistInput";
	foreach ($dbh->query($sql) as $row){
		echo "<form id='deleteForm' name='deleteForm' method='post' action='delete_form.php'>"
		."<label>id</label>";
			echo "<input type='text' name='id' value='$row[id]' size='2' />"
		."<label>Artist Name:</label>";
			echo "<input type='text' name='artist_name' value='$row[artist_name]' />"
		."<label>Artist Description:</label>";
			echo "<input type='text' name='artist_description' value='$row[artist_description]' />"
		."<label>Email:</label>";
			echo "<input type='text' name='email' value='$row[email]' />"
		."<label>Password:</label>";
			echo "<input type='text' name='password' value='$row[password]' />";
			echo "<input type='hidden' name='id' value='$row[id]' />";
	
		echo "<input type='submit' name='submit' value='Delete Artist' class='deleteButton'/>"
		."</form>";
	}
	if ($_REQUEST['submit'] == "Delete Artist"){
		$sql = "DELETE FROM artistInput WHERE id = '$_REQUEST[id]'";
		if ($dbh->exec($sql))
			echo "<p>Query: " . $sql . "</p>\n<p><strong>"
			."<p><a href='artist.php'>Click to add new artist</a></p>"
			."<p><a href='update_form.php'>Click to update an artist</a>";
		
//	if ($dbh->exec($sql))
//		echo "Deleted $_REQUEST[artist_name]";
//	else
//		echo "Not deleted";
	}
	
?>	
	
