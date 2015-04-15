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
<title>Register</title>
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
<h1>Register Form</h1>
<form id="insert" name="insert" method="post" action="process.php">
<fieldset class="subtleSet">
    <h2>Register for our website:</h2>
    <p>
      <label for="email">Email: </label>
      <input type="text" name="email" id="email">
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password">
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Register">
    </p>
</fieldset>
</form>
<?php
echo "</fieldset>\n";
// close the database connection
$dbh = null;
?>
</body>
</html>