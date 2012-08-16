<?php 
	$sID = $_GET['server'];
	$added = 0;
	if (isset($_POST['uName'])) require_once('inc/user/proc.add.php');
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	//Validate the new user form
	$("#addUser").validate();
});
</script>
<h2>Add New User To Server ID <?php echo $sID; ?></h2>
<?php 

	echo '<form name="addUser" method="POST" action="user.php?add=yes&server='.$sID.'">';
	echo "<input type='hidden' value='".$sID."' name='servID' id='servID'/>";
?>
<div id="input">
 <input type="text" id="uName" name="uName" class="required"/><br/>
 <input type="password" id="pass" name="pass"/><br/>
 <input type="text" id="email" name="email"/><br/>
 <select name="aGroup" id="aGroup">
  <option value="rcon">Admin</option>
  <option value="user" selected="selected">User</option>
 </select><a style="color: #0000FF;">?</a><br/>
 <select name="iGroup" id="iGroup">
  <option value="rcon">Admin</option>
  <option value="user" selected="selected">User</option>
 </select><a style="color: #0000FF;">?</a><br/>
 <input type="text" id="steamID" name="steamID"/><br/>
 <input type="text" id="notes" name="notes"/><br/>
</div>
<div id="descript">
Username:<br/>
Password:<br/>
Email:<br/>
Admin Group:<br/>
Immunity Group:<br/>
SteamID:<br/>
Notes:<br/>
</div>
<div style="margin: 0 auto; width: 50px;">
 <input type="submit" value="Add User" id="submit"/>
</div>
</form>
	