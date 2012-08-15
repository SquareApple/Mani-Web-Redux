<?php
 session_start();
	if (!file_exists('inc/config.php')) header("Location: setup/setup.php");
 /* Uncomment the next line to enable error reporting for everything (almost) */
 #error_reporting(~E_ALL);
 
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
 </head>
 <body>
  <div id="content">
   <div id="nav">
    <?php require_once('inc/nav.inc.php'); ?>
   </div>
   <?php
    /* Direct to the apporpate section using GET information */
    if (isset($_GET['users'])) require_once('inc/user.inc.php');
    else if (isset($_GET['updates'])) require_once('inc/updates.proc.php');
    else if (isset($_GET['team'])) require_once('thanks.php');
    else require_once('inc/server.inc.php');  
  ?>