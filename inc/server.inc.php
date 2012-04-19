<h2>Servers</h2>
<table id="serverList">
 <tr class="tHeader">
  <td>Server Name</td><td>IP:Port</td><td>RCon</td><td>Manage</td>
 </tr> 
 <?php
  /* Get the server information and process it */
  if (isset($_GET['servers'])) $server = mysql_real_escape_string($_GET['servers']);
  else $server = 0;
  
  if ($server > 0) {
   $sql->fetchServer($uid, $server);
  }
  else  $sql->fetchServers($uid);
 ?>
</table>
</div>
<?php require_once("inc/footer.php"); ?>
