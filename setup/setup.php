<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Mani Web Redux - Setup</title>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/validate.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				//Validate the config form
				$("#config").validate();
			});
		</script>
		<link rel="stylesheet" href="../styles.css"/>
		<style type="text/css">
			div#content { width: 700px; }
			div#descript { width: 345px; text-align: right; } 
			div#input { width: 345px; float: right; }
			div#nav { width: 690px; } 
			div.error { width: 95%; }
			input#submit { text-align: center; }
			label { font-size: 13pt; }
			label.error{ color: #FF0000; }
		</style>
 </head>
 <body>
  <div id="content">
   <div id="nav" style="text-align: center;">
				<?php echo 'Mani Web Redux Setup'; ?>
   </div>
   <?php
				if (file_exists('../inc/config.php')) { 
					echo '<div class="error" style="margin-top: -15px;"><h3>Config file already exists! Any changes will overwite existing config!</h3></div>';
				}
			?>
			<br/>
			<h3>Database Settings</h3>
			<form method="POST" action="setup.php" id="config" name="config">
				<div id="input">
					<input type="text" id="host" name="host" value="localhost" class="required"/><br/>
					<input type="text" id="user" name="user" class="required"/><br/>
					<input type="password" id="pass" name="pass" class="required"/><br/>
					<input type="text" id="dbname" name="dbname" value="map_db" class="required"/><br/>
					<input type="text" id="tblpre" name="tblpre" value="map_" class="required"><br/>
				</div>
				<div id="descript">
					Database Hostname:<br/>
					Database Username:<br/>
					Database Password:<br/>
					Database Name:<br/>
					Table Prefix:<br/>
				</div>
				<br/>
				<div style="margin: 0 auto; width: 50px;">
					<input type="submit" value="Test settings" id="submit"/>
				</div>
			</form>
  </div>
 </body>
</html>