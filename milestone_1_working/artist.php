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
</style>
</head>
<body>
<?php
include("dbconnect.php");
?>

<h1>Artist Database</h1>
<form id="insert" name="insert" method="post" enctype="multipart/form-data" action="dbdisplayartist.php">
<fieldset class="subtleSet">
    <h2>Insert new artist:</h2>
    <p>
      <label for="artist_name">Artist Name: </label>
      <input type="text" name="artist_name" id="artist_name" />
    </p>
    <p>
      <label for="artist_description">Artist Description: </label>
      <input type="text" name="artist_description" id="artist_description" />
    </p>
    <p>	
    <label for="music_genre">Music genre: </label>
  	<input type="text" name="music_genre" id="music_genre" />
    
    <p>
      <label for="email">Email: </label>
      <input type="text" name="email" id="email" />
    </p>
    Select Image: <br />
    <input type="file" name="image" /><br />
    <p>
      <label for="password">password: </label>
      <input type="password" name="password" id="password" />
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Insert Entry" />
    </p>
</fieldset>
</form>
</fieldset>
</body>
</html>