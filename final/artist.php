<?php
	session_start();
	include("dbconnect.php");
	if (!isset($_SESSION['id'])) {
		header("Location: artist_list.php");
	}
	if ($_SESSION['membership_type'] == 1 || $_SESSION['membership_type'] == 2) {
		
	} else {
		header("Location: artist_list.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php include("inc/header.php"); ?>
    <title>Add New Artist - Townsville Community Music Centre</title>
	<body>
    	<div id="main-wrapper">
            <?php include("inc/navigation.php"); ?>
            <section id="main-content">
                <article>
                    <div>
                        <h1>Artist Database</h1>
                        <form id="insert" name="insert" method="post" enctype="multipart/form-data" action="db_process_artist.php">
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
                            <script type="text/javascript">
                            function select_options(music_genre){
                                if(music_genre=='Other')document.getElementById('hidden_div').innerHTML='Other: <input type="text" name="music_genre" />';
                                    else document.getElementById('hidden_div').innerHTML=''};
                            </script>
                            <label>Genre:</label>
                            <select name="music_genre" id="music_genre" onchange="select_options(this.options[this.selectedIndex].value)">
                                <option value="Rock">Rock</option>
                                <option value="Hip-hop">Hip-hop</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Funk">Funk</option>
                                <option value="Folk">Folk</option>
                                <option value="Reggae">Reggae</option>
                                <option value="Pop">Pop</option>
                                <option value="Dance">Dance</option>
                                <option value="Classic">Classic</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="hidden_div"></div>
                            </p>
                            <p>
                              <label for="email">Contact Info: </label>
                              <input type="text" name="email" id="email" />
                            </p>
                            <p>
                            Select Image: <br />
                            <input type="file" name="image" id = "image" /><br />
                            </p>
                            <p>
                              <input type="submit" name="submit" id="submit" value="Insert Entry" />
                            </p>
                        </fieldset>
                        </form>
                        </fieldset>
                      </div>
            	 </article>
          </section>
          <aside>
              <div class="side-box">
                	 <?php
							$getUserInfo = "SELECT * FROM users WHERE id = $_SESSION[id]";
							$result = $dbh->query($getUserInfo);
							$row = $result->fetch(PDO::FETCH_LAZY);
							if ($row['membership_type'] == 1) {
								echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
							} else {
								echo "<p>Welcome back, ".$row['first_name']."!</p><p>(<a href='profile.php'>Profile</a>)</p><p>(<a href='adminPanel.php'>Control Panel</a>)</p><p>(<a href=logout.php>Logout</a>)<p>";
							}
						?>
           	</div>
          </aside>
          <?php include("inc/sponsor.php"); ?>	
          <?php include("inc/footer.php"); ?>
        </div>
	</body>
</html>