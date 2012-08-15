<?php
	if (isset($_REQUEST['data'])) {
		$serv = "1";
		if (isset($_REQUEST['serv'])) $serv = trim($_REQUEST['serv']);
		$data = trim($_REQUEST['data']);
		require_once('rcon.php');
		$rcon = new rcon;
		$rcon->send($serv,$data);
	}
?>
	