<?php
	include("dbconnect.php");
?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>Artist list</title>
		<style type="text/css">
			.list_format {
				list-style-type:none;
				border:thin;
				border-color:black;
			}
		</style>
    </head>
    <body>
        <header>
        	<h1>Artist</h1>
        </header>
		<section>
			<h2>Genre</h2>
    			<form action="artist_list.php" method="GET">
                    <ul>
                        <?php
                            $sql = "SELECT DISTINCT genre FROM artist";
                            foreach($dbh->query($sql) as $row) {
                                echo '<li style="list-style:none">&raquo; <input type="checkbox" name="genre" value = "', $row['genre'], '"> ', $row['genre'], '</li>';
                            }
                        ?>
                        <input type="submit" name="formSubmit" value="Submit" />
                    </ul>
    			</form>
		</section>
		<section>
		<?php
			if (isset($_GET['genre'])){
    			$selected_genre = $_GET['genre'];
				$result = $dbh->prepare('SELECT * FROM artist WHERE genre = :genre');
				$result->bindValue(':genre', $selected_genre);
				$data = $result->execute();
				foreach($result as $row) {
					echo '<section>';
						echo "<ul class='list_format'>";
							file_put_contents($row['artist_id'] . ".jpg", $row['artist_image']);
							echo "<li><img width='15%' height='15%' src='" . $row['artist_id'] . ".jpg'></li>";
							echo '<li><a href="artist_description.php?id=', $row['artist_id'], '&name=', $row['artist_name'], '"><h2>', $row['artist_name'], '</h2></a></li>';
							echo '<li><p >', $row['genre'], '</p></li>';
						echo "</ul>";
					echo "</section>";
				}
			}
			
			if(!isset($_GET['genre'])){
    			$sql3 = "SELECT * FROM artist";
				foreach($dbh->query($sql3)as $row) {
					echo '<section>';
						echo "<ul class='list_format'>";
							file_put_contents($row['artist_id'] . ".jpg", $row['artist_image']);
							echo "<li><img width='15%' height='15%' src='" . $row['artist_id'] . ".jpg'></li>";
							echo '<li><a href="artist_description.php?id=', $row['artist_id'], '&name=', $row['artist_name'], '"><h2>', $row['artist_name'], '</h2></a></li>';
							echo '<li><p >', $row['genre'], '</p></li>';
						echo "</ul>";
					echo "</section>";
				}
			}
		?>
        </section>
	</body>
</html>