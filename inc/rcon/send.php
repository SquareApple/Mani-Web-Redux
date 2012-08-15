<?php
	if (isset($_POST['data'])) {
		if (isset($_POST['serv'])) {
			$serv = trim($_POST['serv']);
			$data = trim($_POST['data']);
			require_once('rcon.php');
			$rcon = new rcon;
			$rcon->send($serv, $data);
		}
		else echo "<span class=\"error\>Server ID not sent!</span>";
	}
?>
	