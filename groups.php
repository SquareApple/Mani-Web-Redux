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
</head>
<body>
  <div id="content">
    <div id="nav">
         <?php require_once('inc/nav.inc.php'); ?>
    </div>
    <?php
        
    
        if (isset($_GET['mode']) && ((strtolower($_GET['mode']) == 'modify') || (strtolower($_GET['mode']) == 'create'))) {
            require_once('inc/group/proc.php');
        }
        else if ((isset($_GET['sgroup'])) && isset($_GET['group']) && isset($_GET['type'])) {
            $type = $_GET['type'];
            $group = $_GET['group'];
            $sGroup = $_GET['sgroup'];
            if (($type == 'Admin') || ($type == 'Immunity')) require_once('inc/group/modify.php');
        }
        else require_once('inc/group/list.php');
    ?>
