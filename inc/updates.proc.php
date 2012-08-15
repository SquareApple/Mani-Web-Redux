<?php
 /* Needed Vars for message handling */
 $err        = 0;
 $suc        = 0;
 $msg        = '';
 
 /* Filter the update type */
 $updateType = $_GET['updates'];
 
 /* Updating user information */
 if ($updateType == "user") {
  /* Check for unentered $_POST data and if it isn't set, kick an error message */
  if (!isset($_POST['username'])) {
   $err = 1;
   $msg = "Did not receive the username!";
  }
  else if (!isset($_POST['uid'])) {
   $err = 1;
   $msg = "Did not receive the user id";
  }
  else if (!isset($_POST['password'])) {
   $err = 1; 
   $msg = "Did not receive the password";
  }
  else if (!isset($_POST['password2'])) {
   $err = 1;
   $msg = "Did not receive the password confirmation ";
  }
  else if (!isset($_POST['steam'])) {
   $err = 1;
   $msg = "Did not receive the users Steam ID";
  }
  else if (!isset($_POST['notes'])) { 
	$err = 1;
	$msg = "Did not receive notes for the user";
  }
  else if (!isset($_POST['email'])) {
   $err = 1;
   $msg = "Did not receive the email address";
  }
  else {
   /* Escape the strings for semi-safe mysql injection prevention */
   $username = mysql_real_escape_string(trim($_POST['username']));
   $userid   = mysql_real_escape_string(trim($_POST['uid']));
   $email    = mysql_real_escape_string(trim($_POST['email']));
   $pass     = mysql_real_escape_string(trim($_POST['password']));
   $pass2    = mysql_real_escape_string(trim($_POST['password2']));
   $steam    = mysql_real_escape_string(trim($_POST['steam']));
   $notes	 = mysql_real_escape_string(trim($_POST['notes']));
   /* More Checks */
   if ($pass != $pass2) {
    $err = 1;
    $msg = "Passwords do not match!";
   }
   else if (($username == '') || ($userid == '') || ($pass == '')) {
    $err = 1;
    $msg = "You didn't fill out required fields!";
   }
   else {
    /* Update the users information in the database */
    if ($sql->updateUser($userid, $pass, $email, $steam)) {
     if ($sql->updateNotes($userid, $notes)) {
	  $suc = 1;
	  $msg = "Successfully Updated User Information";
	 } 
	 else {
	  $err = 1;
	  $msg = "Failed to update notes"; 
	 }
	}
    else {
     $err = 1;
     $msg = "Failed to update user information!";
    }
   }
   
   /* Message Handling */
   if ($err) echo "<div class=\"error\"><h3>$msg</h3></div>";
   else if ($suc == 1) {
    echo "<div class=\"success\"><h3>$msg</h3></div>";
    
    /* Attempt to pull the user's server group */ 
    if (!($group = $sql->fetchGroup($userid))) echo "<div class=\"error\"><h4>Server must be updated manually!</h4></div>";
    else {
     if (!$servers = $sql->fetchServersInGroup($group)) echo "<div class=\"error\"><h4>Server must be updated manually!!</h4></div>";
     else {
      /* Misc Need vars for output messages (x of x) */
      $serverCount = count($servers);
      $i = 1; 
      
      /* Load the RCon Class */
      require_once('inc/rcon.class.php');
      $rcon = new rcon;
      
      
      echo "<div style=\"width: 500px; margin: 0 auto;\">";
  
      /* Update the user's information for each server in the group */
      foreach($servers as $server) {
       echo "<hr style=\"width: 500px;\"/>";
       echo "<div style=\"text-align: center\">Updating Server ".$i." of ".$serverCount."</div>";
       $rcon->send($server, "ma_client download");
      }
      echo "<hr style=\"width: 500px;\"/><h3>Done</h3></div>";
     }
    }
   }
  }
 }
?>
