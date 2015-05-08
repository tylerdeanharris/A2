<?php
/* This code runs the SQL queries and outputs what happens as a result of the queries.
   It would be possible to have this code set messages in a session variable and pass this on to another page 
   (redirect with the header method) instead of printing the results here. 
   The X option demonstrates this ("silent" delete).
*/
include("dbconnect.php");
$debugOn = true;
	//$sql = "DELETE FROM artistInput WHERE id = '$_REQUEST[id]'";
	//if ($dbh->exec($sql))
		//header("Location: artist.php"); // NOTE: This must be done before ANY html is output, which is why it's right at the top!
/*	else
		// set message to be printed on appropriate (results) page
*/

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP SQLite Database(Artist input) - Results Page</title>
    </head>
    <body>
	<?php
        /* THIS IS FOR TESTING
		echo "<h1>Results</h1>";
        echo "<h2>Form Data</h2>";
        echo "<pre>";
        print_r($_REQUEST); // a useful debugging function to see everything in an array, best inside a <pre> element
        echo "</pre>";
		*/
        $artist_name = $_REQUEST['artist_name'];
        $genre = $_REGUEST['music_genre'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
		
		//Checking if a email is typed
		 if (empty($email)){
            die("Please enter a email address. <a href='artist.php'>Return to database test page</a>");
        }
        //Check to see if entered email is valid entry
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                die("The provided email address is not valid. <a href='artist.php'>Return to database test page</a>");
        }
		//Checking if a artist name is typed
        if (empty($artist_name)){
            die("Please enter an Artist Name. <a href='artist.php'>Return to database test page</a>");
        }
		//Checking if password is typed
		 if (empty($password)){
            die("Please enter password. <a href='artist.php'>Return to database test page</a>");
        }
        // execute the appropriate query based on which submit button (insert) was clicked
        if ($_REQUEST['submit'] == "Insert Entry"){
			
			$file=$_FILES['image']['tmp_name'];
			$image= addslashes(file_get_contents($_FILES['image']['tmp_name']));
			$image_name= addslashes($_FILES['image']['name']);
					
			move_uploaded_file($_FILES["image"]["tmp_name"],"photos/" . $_FILES["image"]["name"]);
					
			$location="photos/" . $_FILES["image"]["name"];
			
            $sql = "INSERT INTO artistInput (artist_name, artist_description, email, music_genre, password, location) VALUES ('$_REQUEST[artist_name]', '$_REQUEST[artist_description]', '$_REQUEST[email]', '$_REQUEST[music_genre]','$_REQUEST[password]', '$location')";
                if ($dbh->exec($sql))
                    echo "<strong>Inserted $_REQUEST[artist_name]</strong>";
                else
                    echo "Not inserted"; // in case it didn't work - e.g. if database is not writeable  
        }
        else {
            echo "This page did not come from a valid form submission.<br />\n";
        }		
        /* THIS IS FOR TESTING 
			// Basic select and display all contents from table 
				echo "<h2>Artist Input in Database Now</h2>\n";
				$sql = "SELECT * FROM artistInput";
				$result = $dbh->query($sql);
				$resultCopy = $result;
				
				if ($debugOn) {
					echo "<pre>";	
				// one row at a time:
				//	$row = $result->fetch(PDO::FETCH_ASSOC);
				//	print_r($row);
				//	echo "<br />\n";
				//	$row = $result->fetch(PDO::FETCH_ASSOC);
				//	print_r($row);
				
				// all rows in one associative array
					$rows = $result->fetchall(PDO::FETCH_ASSOC);
					echo count($rows) . " records in table<br />\n";
					print_r($rows);
					echo "</pre>";
					echo "<br />\n";
				}
       	foreach ($dbh->query($sql) as $row){
            print $row[artist_name] .' - '. $row[artist_description] .' - '. $row[music_genre] .' - '. $row[email] .' - '. $row[password] .' - ' . "<br />\n";
        }
		*/
        // close the database connection 
        $dbh = null;
    ?>
    <p><a href="artist.php">Return to Artist sign up page</a></p>
    <p><a href="delete_form.php">Go to delete Artist</a></p>
    <p><a href="update_form.php">Go to Update Artist data</a></p> 
</body>
</html>