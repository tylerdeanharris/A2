<?php
try {
/* connect to SQLite database. It's good to have this in a separate file you can include in all pages that need DB access */
    $dbh = new PDO("sqlite:a2.sqlite");
	class MyDB extends SQLite3
	{
	  function __construct()
	  {
		 $this->open('a2.sqlite');
	  }
	}
	
	$db = new MyDB();
	if (!$db) {
		echo $db->lastErrorMsg();
	} else {
	  
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
?>