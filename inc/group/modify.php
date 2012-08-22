<table style="width: 900px; margin: 0 auto;">
<?php
    function checked($desc,$flg) {
        echo "<td style='width: 200px'>$desc ($flg)</td><td><input type='checkbox' checked='checked' id='$flg' name='$flg' value='yes'/>";
    }
    function unchecked($desc,$flg) {
        echo "<td style='width: 200px'>$desc ($flg)</td><td><input type='checkbox' id='$flg' name='$flg' value='yes'/>";
    }
    $r = $sql->query('SELECT * FROM '.$sql->prefix.'group WHERE server_group_id = "'.$sGroup.'" AND group_id = "'.$group.'" AND type = "'.$type.'"');
    if ($sql->num_rows($r) > 0) {
        $row = $sql->assoc($r);
        $flagStr = $row['flag_string'];
        $sGroup = $row['server_group_id'];
        $group = $row['group_id'];
        require_once('inc/group/flags.php');
        if ($type == "Immunity") {
            echo "<form method='POST' action='groups.php?mode=modify'>".
            "<input type='hidden' id='type' name='type' value='Immunity'/>".
            "<input type='hidden' id='sgroup' name='sgroup' value='$sGroup'/>".
            "<input type='hidden' id='group' name='group' value='$group'/>".
            "<tr><td colspan='8'><h2>Modifying Immunity Flags</h2></td></tr>".
            "<tr>";
            $i = 0;
            echo "<tr><td colspan='8'>$flagStr</td></tr>";
            foreach ($iFlags as $flag) {
                $i++;
                $t = $i - 1;
                if (($i % 4) == 1) {
                    echo "</tr><tr>";
                }
                if (preg_match("/(^".$flag." | $flag | ".$flag."$)/",$flagStr)) checked($iFDesc[$t],"$flag");
                else unchecked($iFDesc[$t],"$flag");
            }
            echo "</tr><tr><td colspan='8'><input type='submit'/></td></tr>";
        } 
        else if (strtolower($type) == "admin") {
              echo "<form method='POST' action='groups.php?mode=modify'>".
                    "<input type='hidden' id='type' name='type' value='Admin'/>".
                    "<input type='hidden' id='sgroup' name='sgroup' value='$sGroup'/>".
                    "<input type='hidden' id='group' name='group' value='$group'/>".
                    "<tr><td colspan='8'><h2>Modifying Admin Flags</h2></td></tr>".
                    "<tr>";
              
             
              $i = 0;
              echo "<tr><td colspan='8'>$flagStr</td></tr>";
              foreach ($aFlags as $flag) {
                  $i++;
                  $t = $i - 1;
                  if (($i % 4) == 1) {
                      echo "</tr><tr>";
                  }
                  if (preg_match("/(^".$flag." | $flag | ".$flag."$)/",$flagStr)) checked($aFDesc[$t],"$flag");
                  else unchecked($aFDesc[$t],"$flag");
              }
              
        }
    } 
    else {
        echo "<h1>Couldn't find group</h1>";
        die(mysql_error());
    }
?>
</table>