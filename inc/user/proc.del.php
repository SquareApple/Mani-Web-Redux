<?php
	require_once('inc/mysql.class.php');
	$sql		= new sql;
	$delUser 	= mysql_real_escape_string(trim($_GET['uid']));
	$test 	 	= $sql->query("SELECT user_id FROM ".$sql->prefix."client WHERE user_id = \"".$delUser."\"");
	if ($sql->num_rows($test) == 1) {
		$sql->query("DELETE FROM ".$sql->prefix."client WHERE user_id = \"".$delUser."\"") or die(mysql_error());
		$sql->query("DELETE FROM ".$sql->prefix."client_group WHERE user_id = \"".$delUser."\"") or die(mysql_error());
		$sql->query("DELETE FROM ".$sql->prefix."client_server WHERE user_id = \"".$delUser."\"") or die(mysql_error());
		$test 	= $sql->query("SELECT user_id FROM ".$sql->prefix."steam WHERE user_id = '".$delUser."'") or die(mysql_error());
		if ($sql->num_rows($test) == 1) {
			$sql->query("DELETE FROM ".$sql->prefix."steam WERE user_id = '".$delUser."'") or die(mysql_error());
		}
		echo "<div class=\"success\"><h3>User successfully deleted.</h3></div>";
	} else echo "<div class=\"error\"><h3>User with given user id not found!</h3></div>";
?>

