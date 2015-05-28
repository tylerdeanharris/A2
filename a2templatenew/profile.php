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
	include("dbconnect.php");
	// Start a session
	session_start();
	$id = $_SESSION['id'];
	$membership_type = $_SESSION['membership_type'];
	
	// Check to see if there is an active session
	if (!$id) {
		header("Location: index.php");
	}
	
	echo "<title>Townsville Music - Your Profile</title>";
	
	function profile() {
		echo "<form action='profile.php' method='post'>"
		."<h1>Your Profile:</h1>"
		."<table class='table'>"
		."<tr>"
		."<th align='left'>ID:</th>"
		."<td>"; ?><?php global $id; echo $id; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>First Name:</th>"
		."<td>"; ?><?php global $first_name; echo $first_name; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Last Name:</th>"
		."<td>"; ?><?php global $last_name; echo $last_name; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Email:</th>"
		."<td>"; ?><?php global $email; echo $email; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Address:</th>"
		."<td>"; ?><?php global $address; echo $address; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Phone (Daytime):</th>"
		."<td>"; ?><?php global $phone_day; echo $phone_day; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Phone (After Hours):</th>"
		."<td>"; ?><?php global $phone_after_hours; echo $phone_after_hours; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Mobile:</th>"
		."<td>"; ?><?php global $mobile; echo $mobile; ?><?php echo "</td>"
		."</tr>"
		."<tr>"
		."<th align='left'>Membership Type:</th>"
		."<td>"; ?><?php global $membership_type; if($membership_type == 0){echo "Free member <a href='https://www.paypal.com/au/cgi-bin/webscr?cmd=_flow&SESSION=cJqqO8Fem7a6zAm9Fa2GIO_OuErHqjk_yQn3ljcCanMYjMLtnKZLyvTqMWW&dispatch=50a222a57771920b6a3d7b606239e4d529b525e0b7e69bf0224adecfb0124e9b61f737ba21b08198ecd47ed44bac94cd6fd721232afa4155'>(Upgrade)</a>";}else{echo "Paid Member";}; ?><?php echo "</td>"
		."</tr>"
		."</table>"
		."<br>"
		."<input type='submit' value='Update' name='update'>"
		."<input type='submit' value='Back' name='back'>"
		."</form>";
	}
	
	if(isset($_POST['update'])) {
		header("Location: profile_update.php");
	} else if(isset($_POST['back'])) {
		header("Location: index.php");
	}
	
	profile();
?>