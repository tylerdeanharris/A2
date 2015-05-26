<?php
	include("dbconnect.php");
	$debugOn = true;
	session_start();
	if (!isset($_SESSION['id'])) {
		header("Location: artist_list.php");
	} else if ($_SESSION['membership_type'] != 1) {
		header("Location: artist_list.php");
	}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Artist Processing - Townsville Community Music Centre</title>
    </head>
    <body>
	<?php
        $artist_name = $_REQUEST['artist_name'];
        $genre = $_REGUEST['music_genre'];
        $email = $_REQUEST['email'];
		
		//Checking if a email is typed
		 if (empty($email)){
            die("Please enter a email address. <a href='artist.php'>Return to database test page</a>");
        }
        //Check to see if entered email is valid entry
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                die("The provided email address is not valid. <a href='artist.php'>Return to database test page</a>");
        }
		//checking if ententered email already exists.
		$sql = "SELECT email FROM artistInput WHERE email='$email'";
		$email_check = $dbh->query($sql);
		$row = $email_check->fetch(PDO::FETCH_LAZY);
		$db_email = $row['email'];
		if($email == $db_email){
			die("Email already exists, please enter a vaild email address. <a href='artist.php'>Return to database test page</a>");	
			exit();
		}
		//Checking if a artist name is typed
        if (empty($artist_name)){
            die("Please enter an Artist Name. <a href='artist.php'>Return to database test page</a>");
        }
        // execute the appropriate query based on which submit button (insert) was clicked
        if ($_REQUEST['submit'] == "Insert Entry"){
			
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
					//store img on server .
					$file = $_FILES['image']['tmp_name'];
					
					//Checking if photo has alread been stored. 
					if (file_exists("artistPhotos/" . $_FILES["image"]["name"])){
						echo $_FILES["image"]["name"] . " already exists. \n";
					
					} else {						
						$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
						
						$image_name = addslashes($_FILES['image']['name']);
													
						move_uploaded_file($_FILES["image"]["tmp_name"],"artistPhotos/" . $_FILES["image"]["name"]);
							
						$location="artistPhotos/" . $_FILES["image"]["name"];
						
						$sql = "INSERT INTO artistInput (artist_name, artist_description, email, music_genre, location, created_by) VALUES ('$_REQUEST[artist_name]', '$_REQUEST[artist_description]','$_REQUEST[email]','$_REQUEST[music_genre]', '$location', '$_SESSION[id]')";
					
							if ($dbh->exec($sql)){
								//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
								header("Location: artist_list.php");
							
							} else {
									echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
							}
						}
					} 
			//if no imgae has been inserted, assign the artist a default image.		
			}else{
				if(empty($_FILES['image']['name']))	{
					$sql = "INSERT INTO artistInput (artist_name, artist_description, email, music_genre, location, created_by) VALUES ('$_REQUEST[artist_name]', '$_REQUEST[artist_description]','$_REQUEST[email]','$_REQUEST[music_genre]', 'artistPhotos/default.jpg', '$_SESSION[id]')";
					
						if ($dbh->exec($sql)){
							//echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
							header("Location: artist_list.php");
				
						} else {
							echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable 
						}
				//display error message if invalid image has been submitted	
				} else {
				echo "You have entered an invalid Image type. Please enter the following imgae formats: gif, jpeg, png, pjpeg";
				}
			}
		}
	?>
</body>
</html>