<?php
	if (@mysql_connect($dbhost,$dbuser,$dbpass)) { 
		if (@mysql_select_db($dbname)) {
			echo "<div class=\"success\">Connected to MySQL Server!<br/>\r\nConnected to database!<br/>\r\n</div>"; 
			$dbsucc = 1;
		} else {
			echo "<div class=\"error\">Connected to MySQL Server!<br/>\r\nFailed to connect to database! <a href=\"setup.php\">Return To Setup</a><br/>\r\n</div>";
		}
	} else {
		echo "<div class=\"error\">Failed to connect to MySQL Server! Error: ".mysql_error()."<br/>\r\n <a href=\"setup.php\">Return To Setup</a><br/>\r\n</div>";
	}
?>