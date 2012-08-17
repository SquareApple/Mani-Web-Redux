<?php
	class sql {
		/* Database Settings*/
		private $dbuser;
		private $dbpass;
		private $dbhost;
		private $dbname;
		public $prefix;

		/* Misc Stuff */
		public $nQuery = 0;
		/* Startup and sutdown functions */
		function __construct() {
			include 'config.php';
			$this->dbuser = $dbConfig['user'];
			$this->dbpass = $dbConfig['pass'];
			$this->dbhost = $dbConfig['host'];
			$this->dbname = $dbConfig['name'];
			$this->prefix = $dbConfig['prefix'];
			$test = $this->testSettings();
			if ($test != 5) die('Did you edit the config file?');
			else $this->conn();
		}
		/* Connect to the database! */
		private function conn() {
			if (!mysql_connect($this->dbhost, $this->dbuser, $this->dbpass)) die ('Failed to connect to the SQL Server with the specified settings');
			else if (!mysql_select_db($this->dbname)) die ('Failed to select the proper database!');
		}
		/* Test the settings that are given to setup the mysql class in __construct() */
		private function testSettings() {
			if (trim($this->dbuser) == '') return 0;
			else if (trim($this->dbpass) == '') return 1;
			else if (trim($this->dbhost) == '') return 2;
			else if (trim($this->dbname) == '') return 3;
			else if (trim($this->prefix) == '') return 4;
			else return 5;
		}

		/* Login Functions */
		public function checkUserLogin($u, $p) {
			$r = $this->query('SELECT * FROM '.$this->prefix.'client WHERE name = "'.$u.'" AND password = "'.$p.'"');
			if ($this->num_rows($r) == 1) return 1;
			else return 0;
		}
		/* Get users ID information */
		public function getUserID($u) {
			$r = $this->query('SELECT user_id FROM '.$this->prefix.'client WHERE name = "'.$u.'"');
			while ($row = $this->assoc($r)) {
				$id = $row['user_id'];
			}
			return (string)$id;
		}

		/* Data Processing */

		/* Fetch the server group information for a user */
		public function fetchGroup($user) {
			$r = $this->query("SELECT server_group_id FROM ".$this->prefix."client_server WHERE user_id ='".$user."'");
			if ($this->num_rows($r) == 0) return 0;
			while ($row = $this->assoc($r)) {
				$group = $row['server_group_id'];
				return $group;
			}

		}
		/* Fetch Server Information for a user */
		public function fetchServer($u, $s) {
			/* Check to make sure things are set */
			if ($u == '') {
				echo "ERROR: No User ID given!";
				return 0;
			}
			else if (((int)$s == 0) || ($s == '')) {
				echo "ERROR: No Server ID given!";
				return 0;
			}
			/* Get server's group and check for errors */
			$sGroup = $this->getServerGroup($s);
			if ($sGroup == '') {
				echo "ERROR: We have encountered an error processing the server's group";
				return;
			}	 
			/* Get Group and Client Privs */
			$cGroup = $this->getClientGroup($sGroup, $u);
			$cPrivs = $this->getClientPrivs($sGroup,$u);

			/* Misc needed vars */
			$regex  = '/(^r | r | r$)/';
			$level = "none";

			/* Run checks on the privs */
			if ($cGroup != '') {
				$gPrivs = $this->getGroupPrivs($cGroup,$sGroup); 
				if (preg_match($regex, $gPrivs)) $level = "rcon";
			}
			else if (preg_match($regex, $cPrivs)) $level = "rcon";
			if ($level == "rcon") {
				$r = $this->query('SELECT * FROM '.$this->prefix.'server WHERE server_id = "'.$s.'"');
				if ($this->num_rows($r) != 1) {
					echo "ERROR: We cannot find the server!";
				}
					/* Get the information from the data base */
					while ($row = $this->assoc($r)) {
						$serverID     = $row['server_id'];
						$serverName   = $row['name'];
						$serverIP     = $row['ip_address'];
						$serverPort   = $row['port'];
						$serverGroup  = $row['server_group_id'];
						$rcon         = $row['rcon_password'];
						echo "<tr class=\"data\">\r\n<td>".$serverName."</td><td>".$serverIP.":".$serverPort."</td><td>".$rcon."</td><td>---</td>\r\n</tr>\r\n";    
					}
					echo "\r\n</table>\r\n<br/>\r\n";

					/* Pull Users for the server */
					echo "<h2>Users</h2>";
					echo "<table class=\"serverInfo\">\r\n <tr class=\"tHeader\">\r\n  <td class=\"user\">User Name</td><td class=\"ip\">IP</td><td class=\"steam\">Steam</td><td class=\"notes\">Notes</td><td class=\"manage\">Manage</td>\r\n </tr>\r\n";
					$r2 = $this->query('SELECT * FROM '.$this->prefix.'client_server LEFT JOIN '.$this->prefix.'client USING (user_id) LEFT JOIN '.$this->prefix.'steam USING (user_id) LEFT JOIN '.$this->prefix.'ip USING (user_id) WHERE server_group_id ="'.$sGroup.'"');
					if ($this->num_rows($r2) >= 1) {
						while ($row2 = $this->assoc($r2)) {
						$name  = $row2['name'];
						$email = $row2['email'];
						$pass  = $row2['password'];
						$notes = $row2['notes'];
						$steam = $row2['steam_id']; 
						$ip    = $row2['ip_address'];
						$uid   = $row2['user_id'];
						echo "<tr class=\"data\">\r\n<td><a title=\"Email: ".$email."\"a>".$name."</a></td><td>".$ip."</td><td>".$steam."</td><td class=\"notesContent\">".$notes."</td><td><a href=\"index.php?users=".$uid."\">Manage</a> | <a href='user.php?uid=".$uid."&server=".$s."&del=confirm'>Delete</a></td>\r\n</tr>";
					}
				}
				else echo "<tr class=\"data\" colspan=\"5\">\r\n<td><h4>No Users Found</h4></td>\r\n</tr>";     
			}
			else echo "<tr>\r\n<td class=\"data\" colspan=\"4\"><h4>Access Denied!</h4></td>\r\n</tr>";
		}
		/*Pull a list of the servers*/
		public function fetchServers($cid) {
			$r     = $this->query('SELECT * FROM  '.$this->prefix.'server');
			$regex = '/(^r | r | r^)/';
			while ($row = $this->assoc($r)) {
				$serverID     = $row['server_id'];
				$serverName   = $row['name'];
				$serverIP     = $row['ip_address'];
				$serverPort   = $row['port'];
				$serverGroup  = $row['server_group_id'];
				echo "<tr class=\"data\">\r\n<td>".$serverName."</td><td>".$serverIP.":".$serverPort."</td><td>*****</td><td><a href=\"index.php?servers=".$serverID."\">Manage</a></td>\r\n</tr>\r\n";
			}
		}
		public function fetchServersForAdd($u) {
			$r     = $this->query('SELECT * FROM  '.$this->prefix.'server');
			$regex = '/(^r | r | r^)/';
			while ($row = $this->assoc($r)) {
				$s              = $row['server_id'];
				$serverName   = $row['name'];
				$sGroup       = $this->getServerGroup($s);
				if (!$sGroup == '') {
					$cGroup       = $this->getClientGroup($sGroup, $u);
					$cPrivs      = $this->getClientPrivs($sGroup, $u);
					$level       = "none";
					$regex       = '/(^r | r | r$)/';
					if ($cGroup != '') {
						$gPrivs       = $this->getGroupPrivs($cGroup,$sGroup);
						if (preg_match($regex, $gPrivs)) $level = "rcon";
					}
					else if (preg_match($regex, $cPrivs)) $level = "rcon";
					if ($level == "rcon") {
						echo "<tr class=\"data\">\r\n<td>".$serverName."</td><td><a href=\"user.php?server=".$s."&add=yes\">Add</a></td>\r\n</tr>\r\n";
					}
				}
			}
		}
		/* Get the servers in a group */
		public function fetchServersInGroup($group) {
			$r = $this->query("SELECT * FROM ".$this->prefix."server WHERE server_group_id = '".$group."'");
			if ($this->num_rows($r) == 0) return 0;
			$servers = array();
			$i       = 0;
			while ($row = $this->assoc($r)) {
				$servers[$i] = $row['server_id'];
			}
			return $servers;
		}
		/* Get a servers name */
		public function fetchSName($s) {
			$name = '';
			$r = $this->query("SELECT name FROM ".$this->prefix."server WHERE server_id = '".$s."'");
			if ($this->num_rows($r) == 0) return 0;
			while ($row = $this->assoc($r)) {
				$name = $row['name'];
			}
			return $name;
		}
		/* Fetch a List of Servers User Is Allowed to Use RCon On */
		public function fetchRConList($u) {
			$r = $this->query('SELECT * FROM  '.$this->prefix.'server');
			$regex = '/(^r | r | r^)/';
			while ($row = $this->assoc($r)) {
				$serverID     = $row['server_id'];
				$serverName   = $row['name'];
				$serverIP     = $row['ip_address'];
				$serverPort   = $row['port'];
				$sGroup  = $row['server_group_id'];
				if (!$sGroup == '') {
					$cGroup       = $this->getClientGroup($sGroup, $u);
					$cPrivs      = $this->getClientPrivs($sGroup, $u);
					$level       = "none";
					$regex       = '/(^r | r | r$)/';
					if ($cGroup != '') {
						$gPrivs       = $this->getGroupPrivs($cGroup,$sGroup);
						if (preg_match($regex, $gPrivs)) $level = "rcon";
					}
					else if (preg_match($regex, $cPrivs)) $level = "rcon";
					if ($level == "rcon") echo "<tr class=\"data\">\r\n<td>".$serverName."</td><td><a href=\"console.php?serv=".$serverID."\">RCon</a></td>\r\n</tr>\r\n";
					else "<tr class=\"data\">\r\n<td>".$serverName."</td><td>No RCon</td>\r\n</tr>\r\n";
				}
			}
		}
		/* Client Functions */
		/** Fetch a users steam id information **/
		public function fetchSteam($u) {
			$steam = '';
			$r     = $this->query('SELECT * FROM '.$this->prefix.'steam WHERE user_id ="'.$u.'"');
			while ($row = $this->assoc($r)) {
				$steam= $row['steam_id'];
			}
			return $steam;
		}
		/** Fetch Users Email **/
		public function fetchEmail($u) {
			$email = '';
			$r = $this->query('SELECT * FROM '.$this->prefix.'client WHERE user_id="'.$u.'"');
			while ($row = $this->assoc($r)) {
				$email = $row['email'];
			}
			return $email;
			}
		public function fetchNotes($u) {
			$notes = '';
			$r = $this->query('SELECT * FROM '.$this->prefix.'client WHERE user_id="'.$u.'"');
			while ($row = $this->assoc($r)) {
				$notes = $row['notes'];
			}
			return $notes;
		}
		/** Fetch Users Password **/
		public function fetchPass($u) {
			$pass = '';
			$r = $this->query('SELECT * FROM '.$this->prefix.'client WHERE user_id="'.$u.'"');
			while ($row = $this->assoc($r)) {
				$pass = $row['password'];
			}
			return $pass;
		}
		/*Fetch Infromation about the user*/
		public function fetchUserInfo($u) {
			$r = $this->query('SELECT * FROM '.$this->prefix.'client LEFT JOIN '.$this->prefix.'steam USING (user_id) LEFT JOIN '.$this->prefix.'ip USING (user_id) WHERE user_id = "'.$u.'"');
			$rows = $this->num_rows($r);
			/* If there is only one result */
			if ($rows == 1) {
				while ($row = $this->assoc($r)) {
					$name  = $row['name'];
					$email = $row['email'];
					$pass  = $row['password'];
					$notes = $row['notes'];
					$steam = $row['steam_id']; 
					$ip    = $row['ip_address'];
					echo "<tr class=\"data\">\r\n<td><a title=\"Email: ".$email."\"a>".$name."</a></td><td>".$ip."</td><td>".$steam."</td><td class=\"notesContent\">".$notes."</td><td>--</td>\r\n</tr>";
				}
			}
			/* if there is more than one result */
			else if ($rows >= 1) {
				$i = 0;
				while ($row = $this->assoc($r)) {
					$name  = $row['name'];
					$email = $row['email'];
					$pass  = $row['password'];
					$notes = $row['notes'];
					$steam = $row['steam_id']; 
					$ip    = $row['ip_address'];
					if ($i == 0) echo "<tr class=\"data\">\r\n<td><a title=\"Email: ".$email."\"a>".$name."</a></td><td>".$ip."</td><td>".$steam."</td><td class=\"notesContent\">".$notes."</td><td>--</td>\r\n</tr>";
					else echo  "<tr class=\"data\">\r\n<td></td>--<td>".$ip."</td><td>".$steam."</td><td class=\"notesContent\">--</td><td>--</td>\r\n</tr>";

				}
			}
			else echo "<tr class=\"data\">\r\n<td colspan=\"4\"><h4>User Not Found</h4></td>\r\n</tr>\r\n";
		}
		/*Pull the user's group for the specified server*/
		public function getClientGroup($group,$client) {
			$r = $this->query('SELECT group_id FROM '.$this->prefix.'client_group WHERE server_group_id = "'.$group.'" AND user_id = "'.$client.'" AND type = "Admin"');
			$group_id = '';
			while ($row = $this->assoc($r)) {
				$group_id = $row['group_id'];
				return $group_id;
			}
		}
		public function getClientPrivs($group, $client) { 
			$flags = '';
			$r = $this->query("SELECT * FROM ".$this->prefix.'client_flag WHERE user_id ="'.$client.'" AND server_group_id = "'.$group.'" AND type = "Admin"');
			if ($this->num_rows($r) >= 1) {
				$flags = '';
				$i = 0;
				while ($row = $this->assoc($r)) {
					$flags = $row['flag_string'];
				}
			}
			return $flags;
		}
		/* Pull the flags for the client on the server by group*/
		public function getGroupPrivs($client, $server) {
			$r = $this->query('SELECT * FROM '.$this->prefix.'group WHERE group_id = "'.$client.'" AND server_group_id ="'.$server.'" AND type ="Admin"');
			if ($this->num_rows($r) >= 1) {
				$flags = '';
				$i = 0;
				while ($row = $this->assoc($r)) {
					$flags = $row['flag_string'];
				}
				return $flags;
			}
		}
		/* Pull clients (and clients group flags and return a level */
		public function getLevel($u) {
			$level = "user";
			$r = $this->query("SELECT server_group_id FROM ".$this->prefix."client_server WHERE user_id = '".$u."'");
			while ($row = $this->assoc($r)) {
				$s = $row['server_group_id'];
			};
			$cGroup    = $this->getClientGroup($s, $u);
			$gFlag     = $this->getGroupPrivs($cGroup, $s);
			$cFlag     = $this->getClientPrivs($s, $u);
			$rconFlag  = "/(^r | r | r$)/";
			$adminFlag = "/(^admin | admin | admin$)/";
			if (preg_match($rconFlag, $gFlag)) $level = "rcon";
			else if (preg_match($rconFlag, $cFlag)) $level = "rcon";
			else if (preg_match($adminFlag, $gFlag)) $level = "admin";
			else if (preg_match($adminFlag, $cFlag)) $level = "admin";
			return $level;
		}
		/* Pulled needed information from the database about the server to be able to connect via RCon */
		public function getRConInfo($s) {
			$r = $this->query("SELECT * FROM ".$this->prefix."server WHERE server_id = '".$s."'");
			$data = array();
			if ($this->num_rows($r) != 1) die("Failed to get server information! ".mysql_error()); 
			while ($row = $this->assoc($r)) {
				$data['ip']   = $row['ip_address'];
				$data['port'] = $row['port'];
				$data['pass'] = $row['rcon_password'];
			}
			return $data;
			}
		/*Pull the server's group name*/
		public function getServerGroup($server) {
			$r = $this->query('SELECT * FROM '.$this->prefix.'server WHERE server_id ="'.$server.'"');
			$group = '';
			while ($row = $this->assoc($r)) {
				$group = $row['server_group_id'];
			}
			return $group;
		}
		
		/*Get the user name*/
		public function userName($u) {
			$name = '';
			$r = $this->query('SELECT * FROM '.$this->prefix.'client WHERE user_id = "'.$u.'"');
			while ($row  = $this->assoc($r)) { 
				$name = $row['name'];
			}
			return $name;
		}
		/* Update User Information */
		public function updateUser($u, $p, $e, $s) {
			if (!$this->query("UPDATE ".$this->prefix."client SET password='".$p."', email = '".$e."' WHERE user_id='".$u."'")) return 0;
			if (!$this->query("UPDATE ".$this->prefix."steam SET steam_id =\"".$s."\" WHERE user_id = \"".$u."\"")) return 0;
			return 1;
		}
		public function updateNotes($u, $n) {
			if (!$this->query("UPDATE ".$this->prefix."client SET notes = '".$n."' WHERE user_id='".$u."'")) return 0;
				return 1;
			}
		/* Generic MySQL Functions */
		public function assoc($q) {
			return mysql_fetch_assoc($q);
		}
		public function query($q) {
			$this->nQuery++;
			return mysql_query($q);
		}
		public function num_rows($q) {
			return mysql_num_rows($q);
		}	  
	}
?>
