<h2>Updating User Information for <?php echo $username; ?></h2>
<?php 
 /* check to see if the user is authroized to do this action */
 if (($uid == $user) || ($level == "rcon")) {
  /* Fetch user information to update things with */
  $steam_id = $sql->fetchSteam($user);
  $pass      = $sql->fetchPass($user);
  $email     = $sql->fetchEmail($user);
  $notes	 = $sql->fetchNotes($user);
  ?>
  <form name="update" id="update" method="POST" action="index.php?updates=user">
   <?php 
    echo "<input type=\"hidden\" value=\"".$user."\" name=\"uid\" id=\"uid\"/>"; 
   ?>
   <table class="manageUser">
    <tr>
     <td class="label"><label for="username">User name</label></td>
     <td class="input"><?php echo "<input type=\"text\" readonly=\"readonly\" name=\"username\" id=\"username\" value=\"".$username." (Cannot change)\"/>"; ?></td>
    </tr>
    <tr>
     <td class="label"><label for="password">Password</label></td>
     <td class="input"><?php echo "<input type=\"password\" name=\"password\" id=\"password\" value=\"".$pass."\"/>"; ?></td>
    </tr>
    <tr>
     <td class="label"><label for="password2">Confirm</label></td>
     <td class="input"><?php echo "<input type=\"password\" name=\"password2\" id=\"password2\" value=\"".$pass."\"/>"; ?></td>
    </tr>
    <tr>
     <td class="label" ><label for="steam_id" title="Do not change unless you are sure that you have the correct steam id to update to!" class="help">Steam ID</label></td>
     <td class="input"><?php echo "<input type=\"text\" name=\"steam\" id=\"steam\" value=\"".$steam_id."\"/>"; ?></td>
    </tr>
    <tr>
     <td class="label"><label for="email">Email</label></td>
     <td class="input"><?php echo "<input type=\"text\" name=\"email\" id=\"email\" value=\"".$email."\"/>"; ?></td>
    </tr>
	<tr>
	 <td class="label"><label for="notes">Notes:</label></td>
	 <td class="input"><?php echo "<input type=\"text\" id=\"notes\" name=\"notes\" value=\"".$notes."\"/>"; ?></td>
	</tr>
    <tr><td colspan="2" class="submit"><input type="submit" value="Update!"/></td></tr>
   </table>
  </from>
  <?php
 }
 /* User is not authorized so kick an error message */
 else 
 {
  echo "<div class=\"error\">You do not have high enough access to modify other users</div>";
 }
?>