<?php 
/* Check Users Level and if the user is the same one */ 
if (($user == $uid) || ($level == "rcon") || ($level == "admin")) {
 ?>
  <h2>User Information for <?php echo $username; ?></h2>
  <table id="serverList">
   <tr class="tHeader">
    <td class="user">Name</td>
    <td class="ip">IP</td>
    <td class="steam">Steam</td>
    <td class="notes">Notes</td>
    <td class="manage">Manage</td>
   </tr>
   <?php
    /* Fetch the user info from the MySQL database */
    $sql->fetchUserInfo($user);
   ?>
   <tr>
    <td colspan="5"><?php echo "<a class=\"manage\" href=\"index.php?users=".$user."&update=yes\">Update User Info</a>"; ?></td>
   </tr>
  </table>
 <?php
 }
 /* Output an error message if the user is not allowed to access this */
 else echo "<div id=\"error\"><h3>You do not have access for this action</h3></div>";
?>