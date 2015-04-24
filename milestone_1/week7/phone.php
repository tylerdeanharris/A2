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
<title>PHP SQLite Database Example (Phone Records)</title>
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
<h1>Phone Database</h1>
<form id="insert" name="insert" method="post" action="dbprocessphone.php">
<fieldset class="subtleSet">
    <h2>Insert new phone record:</h2>
    <p>
      <label for="name">Name: </label>
      <input type="text" name="name" id="name">
    </p>
    <p>
      <label for="description">Description: </label>
      <input type="text" name="description" id="description">
    </p>
    <p>
      <label for="price">Price: </label>
      <input type="number" name="price" id="price">
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
$sql = "SELECT * FROM information";
foreach ($dbh->query($sql) as $row){
	?>
	<form id="deleteForm" name="deleteForm" method="post" action="dbprocessphone.php">
	<label>id</label>
	<?php
		echo "<input type='text' name='id' value='$row[id]' size='1' />";
	?>
	<label>Name:</label> 
	<?php
		echo "<input type='text' name='name' value='$row[name]' />";
	?>
	<label>Description:</label>
	<?php
		echo "<input type='text' name='description' value='$row[description]' />";
	?>
	<label>Price:</label>
	<?php
		echo "<input type='number' name='price' value='$row[price]' />";
	?>
	<?php
		echo "<input type='hidden' name='id' value='$row[id]' />";
	?>
	<input type="submit" name="submit" value="Update Entry" />
	<input type="submit" name="submit" value="Delete Entry" class="deleteButton">
	<input type="submit" name="submit" value="X" class="deleteButton">
	</form>
	<?php
}
echo "</fieldset>\n";
// close the database connection
$dbh = null;
?>
</body>
</html>