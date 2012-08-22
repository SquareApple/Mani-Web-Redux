<table style="width: 1000px; margin: 0 auto;">
   <tr>
      <td>Server Group</td><td>Type</td><td>Group Name</td><td>Group Priv</td><td>Modify</td>
   </tr>
   <?php
      echo "<tr><td colspan=\"5\"><hr/></td></tr>";
      $r = $sql->query('SELECT * FROM '.$sql->prefix.'group');
      if ($sql->num_rows($r) > 0) {
         while ($row = $sql->assoc($r)) {
            $sGroup  = $row['server_group_id'];
            $group   = $row['group_id'];
            $type    = $row['type'];
            $flags   = $row['flag_string'];
            if ($type == "Admin") {
               echo "<tr style=\"color: #00AA00;\">".
                       "<td>$sGroup</td>".
                       "<td>$type</td>".
                       "<td>$group</td>".
                       "<td>$flags</td>".
                       "<td><a href='groups.php?sgroup=$sGroup&group=$group&type=$type'>Modify</a></td>".
                       "</tr>";
            } elseif ($type == "Immunity") {
                echo "<tr style=\"color: #CC0000;\">".
                       "<td>$sGroup</td>".
                       "<td>$type</td>".
                       "<td>$group</td>".
                       "<td>$flags</td>".
                       "<td><a href='groups.php?sgroup=$sGroup&group=$group&type=$type'>Modify</a></td>".
                       "</tr>";
            }
            echo "<tr><td colspan=\"5\"><hr/></td></tr>";
         }
      }
   ?>
</table>
