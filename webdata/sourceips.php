<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<title>Traffic Sources</title>
	</head>
	<body>
		<div class=header>
                        <h2>LiquidWeb Traffic Monitoring</h2>
                        <h3>Source IPs by Destination</h3>
	                <?php include("includes/menu.php"); ?>
		</div>
		<div class=content>
			<h4>Data displayed here is from the last 7 days, and is updated every 5 minutes.</h4>
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
			<form>
				<p>
			<!-- Service and IP selection form -->
					Please enter the traffic Destination IP address for which you wish to search:
					<br>
					<input type="text" name="formIP">
					<br>
					For which service would you like to see data?
					<br>
					<select name="formPort">
						<option value="0">Select a Service</option>
						<?php echo $options?>
					</select>
					<br>
					<input name="formSubmit" type="submit" value="Submit">
				</p>
			</form>
			<!-- Take the data entered in the form, and run the appropriate queries to retrieve the desired results -->
			<?php
				//Get data from the form submit
				if(isset($_GET['formPort']) && is_numeric($_GET['formPort'])){
				        $varPort = $_GET['formPort'];
				}else{
				        $varPort = 0;
				}
				if(isset($_GET['formIP'])) {
					$varIP = filter_var(trim($_GET['formIP']), FILTER_VALIDATE_IP);
				}
				
				// Create connection
				$conn2 = new mysqli($servername, $username, $password, $dbname);
				
				//Check Connection
				if ($conn2->connect_error) {
				        die("Connection Failed: " . $conn2->connect_error);
				}
				
				//Query
				
				$sql = "SELECT src_ip, dst_ip FROM `rawdata` WHERE `dst_ip` = '".$varIP."' AND `dst_port` = '".$varPort."' AND time > (UNIX_TIMESTAMP() - 86400) GROUP BY src_ip ORDER BY src_ip;";
				
				$result = $conn2->query($sql);
				//If there are results of the query, display them.
				if ($result->num_rows >0) {
				        echo "Now viewing data for Destination Port: $varPort";
				        echo "<table align='center'><tr><th colspan='2'>Destination IP: $varIP <a href=https://billing.int.liquidweb.com/mysql/content/admin/netblock/assignment/search.mhtml?customer=".$varIP."&submit=Search target=_blank>Netblock</a></tr></th><tr><th>Source IPs</th><th>Whois</tr>";
				
				       	while ($row = $result->fetch_assoc()) {
				                echo "<tr><td>".$row["src_ip"]."</td><td><a href=http://utilities.mon.liquidweb.com/netdata/whoislookup.php?formIPaddr=".$row["src_ip"]." target=_blank>Whois</a></tr>";
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
