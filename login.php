<?php
 /* Start a session so that we can track and set sessions when needed */ 
 session_start();
 
 require_once('inc/mysql.class.php');

 /* Misc Needed Vars for message handling */
 $err = 0;
 $suc = 0;
 $msg = '';
  
 /* Log out a user */
 if ((isset($_GET['logout']))  && (isset($_SESSION['mani_admin']))) {
  unset($_SESSION['mani_admin']);
  unset($_SESSION['mani_admin_user']);
  unset($_SESSION['mani_admin_uid']);
  $suc = 1;
  $msg = "You have been logged out!";
 }
 
 /*Log in user test */
 if ((isset($_POST['password'])) && (isset($_POST['username']))) {
  $sql = new sql;
  $pass = $_POST['password'];
  $pass = mysql_real_escape_string($pass);
  $user = $_POST['username'];
  $user2 = mysql_real_escape_string($user);
  /* if user tests successful, login user */
  if ($sql->checkUserLogin($user2, $pass)) {
   if (isset($_SESSION['mani_admin'])) unset($_SESSION['mani_admin']);
   if (isset($_SESSION['mani_admin_user'])) unset($_SESSION['mani_admin_user']);
   if (isset($_SESSION['mani_admin_uid'])) unset($_SSSION['mani_admin_uid']);
   $uid = $sql->getUserID($user2);
   $_SESSION['mani_admin'] = 1;
   $_SESSION['mani_admin_user'] = $user;
   $_SESSION['mani_admin_uid'] = (string)$uid;
   header('Location: index.php');
  }
  else {
   $err = 1;
   $msg = 'Wrong user/password combo';
  }
  
 }
?>
<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="styles.css"/>
  <title>Mani Web Redux - Login</title>
 </head>
 <body>
  <div class="login">
   <img src="img/logo.png" id="logo" height="100px" width="550px" alt="Logo" />
   <form method="POST" action="login.php" id="login" name="login">
    <div class="form">
     <?php 
     /* Message handling */ 
     if($err) {
       echo '<div class="error">'.$msg."</div>\r\n";
      }
      else if ($suc) {
       echo "<div class=\"success\">".$msg."</div>\r\n";
      }
      else echo "<br/>\r\n";
     ?>
     User:<br/> 
     <input type="text" name="username" id="username"/><br/>
     Pass:<br/>
     <input type="password" name="password" id="password"/><br/>
     <input type="submit" value="Login" id="submitLogin" name="submitLogin"/>
    </div>
   </form>
  </div>
 </body>
</html>
