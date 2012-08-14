<?php
	$confFile = "../inc/config.php";
	$backFile = "../inc/config.php.old";
	$fdata = 
'<?php
#This file was automaticly generated#
$dbConfig = array(
 "user" => "'.$dbuser.'",
 "pass" => "'.$dbpass.'",
 "host" => "'.$dbhost.'",
 "name" => "'.$dbname.'",
 "prefix" => "'.$tblpre.'"
);
?>';					
	function writeData($fname, $contents) { 
		if($hand = fopen($fname,'w')) {
				if (fwrite($hand,$contents)) echo "<div class=\"success\">Config file successfully generated!</div><br/>\r\n";
				else "<div class=\"error\">Unable to write config file</div>";
		} 
		else echo "<div class=\"error\">Unable to open file</div>";
	}
	if (file_exists($confFile)) { 
		if (@rename($confFile,$backFile)) {
				writeData($confFile,$fdata);
		} else	{
			echo "<div class=\"error\">Unable to write to config file.</div>";
			echo "<h2>Attempting to write file to setup directory</h2>";
			echo "<h4>Please place the generated config file into the inc/ directory if successful</h4>";
			writeData("config.php",$fdata);
		}
	}
?>