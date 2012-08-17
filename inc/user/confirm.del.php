<?php
	$uConf	= $_GET['uid'];
	$sID  	= $_GET['server'];
	require_once('inc/mysql.class.php');
	$sql 	= new sql;
?>
<h3>Are you sure you want to delete <?php echo $sql->userName($uConf); ?>?</h3>
<?php echo '<h3><a href="user.php?del=yes&server='.$sID.'&uid='.$uConf.'">Yes</a>'; ?>  |  <a href="index.php">No</a></h3>
