<?php
	session_start();
	if (!file_exists('inc/config.php')) header("Location: setup/setup.php");
	/* Uncomment the next line to enable error reporting for everything (almost) */
	error_reporting(~E_ALL);
	 
	/* Check to see if the admin session is setup, and if it is set some values */
	if (!isset($_SESSION['mani_admin'])) header("Location: login.php");
	$user = $_SESSION['mani_admin_user'];
	$uid  = $_SESSION['mani_admin_uid'];
	 
	/* Load the MySQL class file and get the users level */
	require_once('inc/mysql.class.php');
	$sql      = new sql;
	$level    = $sql->getLevel($uid);
 
	$conVer = "MWR Live RCon Console Version 0.1";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Mani Web Redux - Live Console</title>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			function send() {
				var d = $("#data").val();
				$('#temp').load('inc/rcon/send.php', { 'data': d }, function(response, status, xml) {
					$('#out').append(response);
				});
			}
		</script>
		<link rel="stylesheet" href="styles.css"/>
	</head>
	<body>
		<div id="content" style="padding-bottom: 10px;">
			<div id="nav">
				<?php require_once('inc/nav.inc.php'); ?>
			</div>
			<div id="temp" name="temp" style="display: none;"></div>

			<div id="top" name="top" style="background-color: #000000; width: 600px; height: 400px; border: 1px solid #000; margin: 0 auto; overflow: scroll;">
				<div id="out" style="padding: 0px 15px;">
					<span style="font-style: italic; font-weight: bolder; color: #FF0000"><?php echo $conVer; ?></span><br/>
				</div>
			</div>
			<div style="width: 600px; border: 1px solid #000; margin: 0 auto;">
				<div style="padding:5px;">
					<textarea id="data" name="data" cols="70" rows="1"></textarea><input type="submit" value="Send" onclick="send();"/>
				</div>
			</div>
		</div>
		<?php require_once('inc/footer.php');