<?php
include("dbconnect.php")
/* Fairly simple example - there's a form for inserting a new phone record and a set of forms, one for each record,
	that allows for deleting and updating each record. In these ones, the id of the record is passed using a hidden form field. 
*/
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP SQLite Database music artists</title>
<style type="text/css">
.subtleSet {
	border-radius:25px;
	width: 30em;
}
.deleteButton {
	color: red;
}
</style>
</head>

<body>
<h1>Artist Database</h1>
<form id="insert" name="insert" method="post" action="dbdisplayartist.php">
<fieldset class="subtleSet">
    <h2>Insert new artist:</h2>
    <p>
      <label for="artist_name">Artist Name: </label>
      <input type="text" name="artist_name" id="artist_name">
    </p>
    <p>
      <label for="artist_description">Artist Description: </label>
      <input type="text" name="artist_description" id="artist_description">
    </p>
    <p>
      <label for="insert_img">Insert image: </label>
      <input type="text" name="insert_img" id="insert_img">
    </p>
    <p>
      <label for="email">Email: </label>
      <input type="text" name="email" id="email">
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Insert Entry">
    </p>
</fieldset>
</form>

<fieldset class="subtleSet">
<h2>Current data:</h2>
<?php
// Display what's in the database at the moment.
$sql = "SELECT * FROM artistInput";
foreach ($dbh->query($sql) as $row){
	?>
	<form id="deleteForm" name="deleteForm" method="post" action="dbdisplayartist.php">
	<label>id</label>
	<?php
		echo "<input type='text' name='id' value='$row[id]' size='2' />";
	?>
	<label>Artist Name:</label> 
	<?php
		echo "<input type='text' name='artist_name' value='$row[artist_name]' />";
	?>
	<label>Artist Description:</label>
	<?php
		echo "<input type='text' name='artist_description' value='$row[artist_description]' />";
	?>
	<label>Image:</label>
	<?php
		echo "<input type='image' name='insert_img' value='$row[insert_img]' />";
	?>
	<?php
		echo "<input type='hidden' name='id' value='$row[id]' />";
	?>
	<input type="submit" name="submit" value="Update Entry"/>
	<input type="submit" name="submit" value="Delete Entry" class="deleteButton"/>
	<input type="submit" name="submit" value="X" class="deleteButton"/>
	</form>
	<?php
}
echo "</fieldset>\n";
// close the database connection
$dbh = null;
?>
</body>
</html>