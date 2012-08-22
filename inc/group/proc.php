<?php
    $type = strtolower(trim($_POST['type']));
    $mode = strtolower(trim($_GET['mode']));
    if ((isset($_POST['sgroup'])) && isset($_POST['group'])) {
        require_once('inc/group/flags.php');
        $sGroup = $_POST['sgroup'];
        $group  = $_POST['group'];
        if ($type == "immunity") {
            $flags = '';
            foreach ($iFlags as $flag) {
                if (isset($_POST[$flag])) $flags .= " $flag"; 
            }
            #echo "Set Flags are: $flags";
            if ($mode == 'modify') {
                $sql->query("UPDATE ".$sql->prefix."group SET flag_string = '$flags' WHERE server_group_id = '$sGroup' AND group_id = '$group' AND type = 'Immunity'") or die(mysql_error());
                echo "Updated Flags!";
            }
            else if ($mode == 'add') {
                $r = $sql->query("SELECT * FROM ".$sql->prefix."group WHERE server_group_id = '$sGroup' AND group_id = '$group' AND type = 'Immunity'") or die(mysql_error());
                if ($sql->num_rows($r) > 0) {
                    echo "<h1>Creation of group aborted!</h1><h3>Group Exists!</h3>";
                }
                else {
                    $sql->query("INSERT INTO ".$sql->prefix."group (server_group_id, group_id,type,flag_string) VALUES('$sGroup','$group','Immunity','$flags'") or die(mysql_error());
                    echo "<h1>Added new group!</h1>";
                }
            }
        }
        else if ($type == "admin") {
            $flags = '';
             foreach ($aFlags as $flag) {
                if (isset($_POST[$flag])) $flags .= " $flag"; 
            }
            if ($mode == 'modify') {
               $sql->query("UPDATE ".$sql->prefix."group SET flag_string = '$flags' WHERE server_group_id = '$sGroup' AND group_id = '$group' AND type = 'Admin'") or die(mysql_error());
               echo "Updated flags!";
            } 
            else if ($mode == 'add') {
                $r = $sql->query("SELECT * FROM ".$sql->prefix."group WHERE server_group_id = '$sGroup' AND group_id = '$group' AND type = 'Admin'") or die(mysql_error());
                if ($sql->num_rows($r) > 0) {
                    echo "<h1>Creation of group aborted!</h1><h3>Group Exists!</h3>";
                }  
                else {
                    $sql->query("INSERT INTO ".$sql->prefix."group (server_group_id, group_id,type,flag_string) VALUES('$sGroup','$group','Admin','$flags'") or die(mysql_error());
                    echo "<h1>Added new group!</h1>";
                }
            }
        }
        else {
            echo "bleh";
        }
    }
?>
