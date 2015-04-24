<?php
	include("dbconnect.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo $_GET['name'];?></title>
</head>
<body>
<p><a href="artist_list.php">Back to artist page</a></p>
<?php
	echo"<h1>". $_GET["name"]. "</h1>";
	$sql = "SELECT * FROM artist WHERE artist_id = $_GET[id]";
	
	foreach($dbh->query($sql) as $row){
		echo'<section>';
	  	file_put_contents($row['artist_id'].".jpg",$row['artist_image']);
	  	echo "<img width='20%' height='20%' src='".$row['artist_id'].".jpg'>";
		echo'<h2>' . $row['artist_name'] . '</h2>';
		echo '<p> ' . $row['artist_details'] . '</p>';
		echo 'Email address: ' . $row['artist_email'];
		echo"</section>";
	}
?>
</body>
</html>