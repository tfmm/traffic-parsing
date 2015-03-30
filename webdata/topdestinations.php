<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<title>Traffic Monitoring</title>
		<?php
			$varPort2 = $_GET['formPort'];
			if($varPort2 != ""){
				echo "<meta http-equiv=\"refresh\" content=\"90; topdestinations.php?formPort=" . $varPort2. "\">";
				}
			?>
	</head>
	<body>
		<div class=header>
                        <h2>LiquidWeb Traffic Monitoring</h2>
                        <h3>Top Destination IPs</h3>
	                <?php include("includes/menu.php"); ?>
		</div>
		<div class=content>
			<h2>Busiest Internal IPs by number of inbound connections</h2>
			<h3>YOU MUST SELECT A SERVICE TO VIEW DATA!</h3>
			<h4>Data displayed here is from the last 30 minutes, and is updated every 5 minutes.</h4>
			<!-- MySQL connection to retrieve data for the service selection dropdown -->
			<?php
				//Include DB Connection Info
				include("includes/db_config.php");
				
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				
				//Create dropdown
				$query = "SELECT port, svc_name FROM commonports ORDER BY port";
				$tbllist = mysqli_query($conn, $query);
				$options = "";
				while ($row = mysqli_fetch_array($tbllist))
				{
					$id=$row["port"];
					$name=$row["svc_name"];
					$options.="<option value=\"$id\">$id - $name</option>";
				}
				
				//Check Connection
				if ($conn->connect_error) {
					die("Connection Failed: " . $conn->connect_error);
				}
				$conn->close();
				?>
			<p>
			<!-- Service selection form -->
			<form>
				<p>
					For which service would you like to see data?
					<br>
					<select name="formPort">
						<option value=0>Select a service</option>
						<?php echo $options?>
					</select>
					<br>
					<input name="formSubmit" type="submit" value="Submit">
				</p>
			</form>
			<!-- Take submitted data, run the necessary MySQL Queries -->
			<?php
				//Get data from the form submit
				if(isset($_GET['formPort'])){
				        $varPort = $_GET['formPort'];
				}else{
				        $varPort = 0;
				}
				// Create connection
				$conn2 = new mysqli($servername, $username, $password, $dbname);
				
				//Query
				
				$sql = "SELECT dst_ip, COUNT(DISTINCT(src_ip)), COUNT(dst_ip) FROM `rawdata` WHERE `dst_port` = '".$varPort."' AND time > (UNIX_TIMESTAMP() - 1800) GROUP BY dst_ip ORDER BY COUNT(DISTINCT(src_ip)) DESC LIMIT 50";
				
				$result = $conn2->query($sql);
				//If there are results, display them in a table
				if ($result->num_rows >0) {
					echo "Now viewing data for Destination Port $varPort";
					echo "<br>";
					echo "<table align='center'><tr><th>Destination IP</th><th># of sources</th><th># of connections</th><th>Destination Netblock</th><th>Source IPs</th></tr>";
					while ($row = $result->fetch_assoc()) {
						echo "<tr><td>".$row["dst_ip"]."</td><td>".$row["COUNT(DISTINCT(src_ip))"]."</td><td>".$row["COUNT(dst_ip)"]."</td><td><a href=https://billing.int.liquidweb.com/mysql/content/admin/netblock/assignment/search.mhtml?customer=".$row["dst_ip"]."&submit=Search target=_blank>Netblock</a></td><td><a href=http://utilities.mon.liquidweb.com/netdata/sourceips.php?formIP=".$row["dst_ip"]."&formPort=$varPort target=_blank>List</a></td></tr>";
						}
						echo "</table>";
				} else {
					echo "No Results, something is likely broken.";
				}
				$conn2->close();
				?>
		</div>
	</body>
</html>
