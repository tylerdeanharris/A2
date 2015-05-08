<?php
	try {
	/* connect to SQLite database. It's good to have this in a separate file you can include in all pages that need DB access */
		$dbh = new PDO("sqlite:a2.sqlite");
		echo "<p><a href='artist.php'>Back</a></p>";//link back to home
	}
	catch(PDOException $e)
	{
		echo "Database has failed to be created", $e->getMessage();
	}
?>	