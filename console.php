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
			//Ajax Loading of RCon Data//
			function send() {
				var d = $("#data").val();
				<?php 
					$serv = 0;
					if (isset($_GET['serv'])) {
						$serv = (int)trim($_GET['serv']);
					}
					echo "var s = ".$serv.";";
				?>
				$('#temp').load('inc/rcon/send.php', { 'data': d, 'serv': s }, function(response, status, xml) {
					$('#out').append(response);
				});
			}
			$(document).ready(function() {
				var hide = 0;
				//Show/Hide Server List//
				$('#sListToggle').click(function() {
					//Hide the list//
					if (hide == 0) {
						$('#rconList').slideToggle('slow',function() {
							$('#sListToggle').html('Click To Show Server List');
							hide = 1;			
						});
					//Show the list//
					} else { 
						$('#rconList').slideToggle('slow',function() {
							$('#sListToggle').html('Click To Hide Server List');
							hide = 0;			
						});
					}
				});
			});
		</script>
		<link rel="stylesheet" href="styles.css"/>
	</head>
	<body>
		<div id="content" style="padding-bottom: 10px;">
			<div id="nav">
				<?php require_once('inc/nav.inc.php'); ?>
			</div>
			<?php
				if ($level == "rcon") {
			?>
				<div id="temp" name="temp" style="display: none;"></div>
				<div id="rconList">
					<table width="500px">
						<tr>
							<td>Server</td>
							<td>RCon</td>
						</tr>
						<?php
							$sql->fetchRConList($uid);
						?>
					</table>
				</div>
				<div id="sListToggle" name="sListTroggle" style="text-align: center;">
					Click Here to Hide Server List
				</div>
				<?php 
					if (isset($_GET['serv'])) {
						$sName = $sql->fetchSName($serv);
				?>	
					<div id="top" name="top" style="background-color: #000000; width: 600px; height: 400px; border: 1px solid #000; margin: 0 auto; overflow: scroll;">
						<div id="out" style="padding: 0px 15px;">
							<span style="font-style: italic; font-weight: bolder; color: #FF0000"><?php echo $conVer; ?><br/>Starting session for <?php echo $sName; ?></span><br/>
						</div>
					</div>
					<div style="width: 600px; border: 1px solid #000; margin: 0 auto;">
						<div style="padding:5px;">
							<textarea id="data" name="data" cols="70" rows="1"></textarea><input type="submit" value="Send" onclick="send();"/>
						</div>
					</div>
			<?php
					}
				} else echo "<h1>You do not have any RCON Privs!</h1>"; 
			?>
		</div>
		<?php require_once('inc/footer.php');