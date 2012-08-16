<a href="index.php">Home</a>
<?php 
	if (($level == "rcon") || ($level == "admin")) {
		echo " | ";
		echo "<a href=\"console.php\">RCon</a>";
		echo " <a href=\"user.php\">Add New User</a>";
	}
	echo ' | <a href="index.php?users='.$uid.'&update=yes">Update Account Info</a> | '; 
?>
<a href="index.php?team=1">The MWR Team</a> | 
<a href="login.php?logout=1">Logout</a>