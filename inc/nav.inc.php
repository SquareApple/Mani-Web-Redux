<a href="index.php">Home</a>
<?php 
 echo '<a href="index.php?users='.$uid.'&update=yes">Update Account Info</a> '; 
 if (($level == "rcon") || ($level == "admin")) echo "<a class=\"manage\" href=\"adduser.php\">Add New User</a>";
?>
<a href="index.php?team=1">The MWR Team</a>
<a href="login.php?logout=1">Logout</a>