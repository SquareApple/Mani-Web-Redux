			<br/>
			<h3>Database Settings</h3>
			<form method="POST" action="setup.php" id="config" name="config">
				<div id="input">
					<input type="text" id="dbhost" name="dbhost" value="localhost" class="required"/><br/>
					<input type="text" id="dbuser" name="dbuser" class="required"/><br/>
					<input type="password" id="dbpass" name="dbpass" class="required"/><br/>
					<input type="text" id="dbname" name="dbname" value="map_db" class="required"/><br/>
					<input type="text" id="tblpre" name="tblpre" value="map_" class="required"><br/>
				</div>
				<div id="descript">
					Database Hostname:<br/>
					Database Username:<br/>
					Database Password:<br/>
					Database Name:<br/>
					Table Prefix:<br/>
				</div>
				<br/>
				<div style="margin: 0 auto; width: 50px;">
					<input type="submit" value="Test settings" id="submit"/>
				</div>
			</form>