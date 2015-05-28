<style type="text/css">
h1 {
	padding-top: 3%;
	margin-left: 3%;
}
.table {
	margin-left: 3%;
}
.table th {
	padding-top: 5%;
}
.table td {
	padding-top: 5%;
	padding-left: 5%;
}
a {
	margin-left: 3.3%;
	text-decoration: none;
	color: #F00;
}
a:hover {
	font-weight: bold;
}
</style>
<?php
	error_reporting(E_ALL); ini_set('display_errors', 1);
	/* This code runs the SQL queries and outputs what happens as a result of the queries.
	   It would be possible to have this code set messages in a session variable and pass this on to another page 
	   (redirect with the header method) instead of printing the results here. 
	   The Delete option demonstrates this ("silent" delete).
	*/
	include("dbconnect.php");
	
	// Start a session
	session_start();
	$id = $_SESSION['id'];
	$first_name = $_SESSION['first_name'];
	$last_name = $_SESSION['last_name'];
	$email = $_SESSION['email'];
	$address = $_SESSION['address'];
	$phone_day = $_SESSION['phone_day'];
	$phone_after_hours = $_SESSION['phone_after_hours'];
	$mobile = $_SESSION['mobile'];
	$password = $_SESSION['password'];
	$membership_type = $_SESSION['membership_type'];
	
	// Check to see if there is an active session
	if (!$email && !$password) {
		header("Location: index.php");
	}
	
	echo "<title>Townsville Music - Edit Your Profile</title>";
	
	function edit() {
		global $first_name, $last_name, $email, $address, $phone_day, $phone_after_hours, $mobile;
		echo "<form action='edit.php' method='post'>"
		."<h1>Edit Your Profile:</h1>"
		."<table class='table'>"
		."<tr>"
		."<th align='left'>ID:</th>"
		."<td>"; ?><?php global $id; echo $id; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>First Name:</th>"
		."<td><input type='text' name='first_name'value='" . $first_name . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Last Name:</th>"
		."<td><input type='text' name='last_name' value='" . $last_name . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Email:</th>"
		."<td><input type='text' name='email' value='" . $email . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Address:</th>"
		."<td><input type='text' name='address' value='" . $address . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Phone (Daytime):</th>"
		."<td><input type='text' name='phone_day' value='" . $phone_day . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Phone (After Hours):</th>"
		."<td><input type='text' name='phone_after_hours' value='" . $phone_after_hours . "' size='30'></td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Mobile:</th>"
		."<td><input type='text' name='mobile' value='" . $mobile . "' size='30'></td>"
		."</tr>"
		."</table>"
		."<br>"
		."<input type='submit' value='Save' name='save'>"
		."<input type='submit' value='Delete' name='delete'>"
		."<input type='submit' value='Back' name='back'>"
		."</form>";
	}
	
	// execute the appropriate query based on which submit button (insert, delete or update) was clicked
	function removeUser() {
		include("dbconnect.php");
		global $id;
		$sql3 = "DELETE FROM users WHERE id = $id";
		if ($dbh->query("DELETE FROM users WHERE id = $id")) {
			echo "Deleted profile";
		} else {
			echo "Not deleted";
		}
	}
	
	function save() {
		error_reporting(E_ALL); ini_set('display_errors', 1);
		include("dbconnect.php");
		$sql = "UPDATE users SET first_name = '$_REQUEST[first_name]' WHERE id = $_SESSION[id]";
		if ($dbh->exec($sql)) {
			die("Saved $_REQUEST[first_name]");
		} else {
			print_r($sql);
			die("failed");
		}
	}
	
	if(isset($_POST['save'])) {
		save();
	} else if(isset($_POST['delete'])) {
		removeUser();
	} else if(isset($_POST['back'])) {
		header("Location: profile.php");
	}
	
	edit();
	
?>