<?php
try {
/* connect to SQLite database. It's good to have this in a separate file you can include in all pages that need DB access */
    $dbh = new PDO("sqlite:userinput.sqlite");
	message("Database successfully created"); //test
}
catch(PDOException $e)
{
    message("Database has failed to be created", $e->getMessage());
}
?>	