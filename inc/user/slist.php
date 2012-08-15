  <h2>Servers</h2>
   <table id="serverList" style="width: 500px;">
    <tr class="tHeader">
     <td>Server Name</td><td>Manage</td>
    </tr> 
   <?php
	$sql->fetchServersForAdd($uid);
   ?>
   </table>
 