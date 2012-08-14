<?php
	/* Uncomment the next line to enable error reporting for everything (almost) */
 error_reporting(~E_ALL);
?>
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
			div#descript { width: 345px; text-align: right; line-height: 135%;} 
			div#input { width: 345px; float: right; }
			div#nav { width: 690px; } 
			div.success { width: 75%; text-align: center; }
			div.error { width: 75%; text-align: center; }
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
				if (isset($_POST['dbhost'])) {
					$dbhost	= $_POST['dbhost'];
					$dbuser	= $_POST['dbuser'];
					$dbpass	= $_POST['dbpass'];
					$dbname = $_POST['dbname'];
					$tblpre =	$_POST['tblpre'];
					$dbsucc = 0;
					echo "<h2>Testing MySQL Connection</h2>\r\n";
					require_once('test.mysql.php');
					if ($dbsucc == 1) { 
						echo "<h2>Testing Writing to file</h2>";
						include 'writefile.php';
					}
				} else require_once('form.php');
			?>
  </div>
 </body>
</html>