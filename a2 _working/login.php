<?php
	include("dbconnect.php")
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
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
<h1>Login Form</h1>
<form id="insert" name="insert" method="post" action="check.php">
<fieldset class="subtleSet">
    <h2>Login to Our Website:</h2>
    <p>
      <label for="email">Email: </label>
      <input type="text" name="email" id="email">
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password">
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Login">
    </p>
</fieldset>
</form>
<?php
echo "</fieldset>\n";
// Close the database connection
$dbh = null;
?>
</body>
</html>