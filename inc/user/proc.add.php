<?php
	$uName	= $_POST['uName'];
	$sGroup	= $sql->getServerGroup($sID);
	$uPass 	= '';
	$aGroup = '';
	$iGroup	= '';
	$uSteam	= '';
	$uNotes	= '';
	if (isset($_POST['pass'])) $uPass		= mysql_real_escape_string(trim($_POST['pass']));
	if (isset($_POST['aGroup'])) $aGroup 	= mysql_real_escape_string(trim($_POST['aGroup']));
	if (isset($_POST['iGroup'])) $iGroup 	= mysql_real_escape_string(trim($_POST['iGroup']));
	if (isset($_POST['steamID'])) $uSteam 	= mysql_real_escape_string(trim($_POST['steamID']));
	if (isset($_POST['notes'])) $uNotes		= mysql_real_escape_string(trim($_POST['notes']));
	
	$r = $sql->query('SELECT * FROM '.$sql->prefix.'client WHERE name = "'.$uName.'"');
	if ($sql->num_rows($r) > 0) echo "User already exists!";
	else {
		$sql->query('INSERT INTO '.$sql->prefix.'client (name, password, email, notes) VALUES ("'.$uName.'","'.$uPass.'","'.$uEmail.'","'.$uNotes.'")') or die(mysql_error());
		$uData = $sql->query('SELECT * FROM '.$sql->prefix.'client WHERE name = "'.$uName.'"') or die(mysql_error());
		$row = $sql->assoc($uData);
		$uID = $row['user_id'];
		echo "userID:".$uID."<br/>";
		$sql->query('INSERT INTO '.$sql->prefix.'client_server (user_id, server_group_id) VALUES ("'.$uID.'","'.$sGroup.'")') or die(mysql_error());
		$sql->query('INSERT INTO '.$sql->prefix.'client_group (user_id, server_group_id, group_id, type) VALUES ("'.$uID.'","'.$sGroup.'","'.$aGroup.'", "Admin")') or die(mysql_error());
		$sql->query('INSERT INTO '.$sql->prefix.'client_group (user_id, server_group_id, group_id, type) VALUES ("'.$uID.'","'.$sGroup.'","'.$iGroup.'", "Immunity")') or die(mysql_error());
		if($uSteam != '') $sql->query('INSERT INTO '.$sql->prefix.'steam (user_id, steam_id) VALUES ("'.$uID.'","'.$uSteam.'")') or die(mysql_error());
		echo "<div class=\"success\"><h3>User added!<br/>Please issue \"ma_client download\" on all servers in this group via RCon!</h3></div>";
	}
?>
	