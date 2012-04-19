<?php
 /* Set some variables */
 $user     = $_GET['users'];
 $username = $sql->userName($user);
 /*Direct to the proper sub-script */
 if (isset($_GET['update'])) require_once('inc/user.update.php');
 else require_once('inc/user.info.php');
?>
</div>
<?php require_once("inc/footer.php"); ?>