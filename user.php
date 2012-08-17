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
 
?>
<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Mani Web Redux - Admin Panel</title>
  <link rel="stylesheet" href="styles.css"/>
  <style type="text/css">
	div#content { width: 700px; }
	div#descript { width: 345px; text-align: right; line-height: 135%;} 
	div#input { width: 345px; float: right; }
	div#nav { width: 690px; } 
	div.error { width: 75%; text-align: center; }
	div.footer { width: 700px; }
	div.success { width: 75%; text-align: center; }
	input#submit { text-align: center; }
	label { font-size: 13pt; }
	label.error{ color: #FF0000; }
  </style>
 </head>
 <body>
  <div id="content">
   <div id="nav">
    <?php require_once('inc/nav.inc.php'); ?>
   </div>
   <?php 
    if (isset($_GET['server'])) {
		if (isset($_GET['add'])) require_once('inc/user/add.php');
		else if (isset($_GET['del'])) {
			$type = $_GET['del'];
			$allowed = array('confirm','yes');
			if (in_array($type,$allowed)) {
				if ((isset($_GET['uid'])) && ($type == 'confirm')) require_once('inc/user/confirm.del.php');
				else if ((isset($_GET['uid'])) && ($type == 'yes')) require_once('inc/user/proc.del.php');
			}
		}
	}
	else require_once('inc/user/slist.php');
   ?>
  </div>
  <?php require_once('inc/footer.php'); ?>