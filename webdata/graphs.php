<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<title>Traffic Graphing</title>
                <?php
                        $varPort2 = $_GET['formPort'];
                        if($varPort2 != ""){
                                echo "<meta http-equiv=\"refresh\" content=\"900; graphs.php?formPort=" . $varPort2. "\">";
                                }
                        ?>
	</head>
	<body>
		<div class=header>
                        <h2>Traffic Monitoring</h2>
                        <h3>Inbound Traffic Graphs</h3>
	                <?php include("includes/menu.php"); ?>
		</div>
		<div class=content>
			<h3>You must select a service to view data.</h3>
			<h4>These graphs illustrate the total number of incoming connections for the selected service.</h4>
			<!-- MySQL connection to retrieve data for the service selection dropdown -->
			<?php
				//Include DB connection info
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
			<!-- Service Selection form -->
				<p>
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
			<!-- Take submitted data, run the queries to get desired data -->
			<?php
				//Get data from the form submit
				if(isset($_GET['formPort']) && is_numeric($_GET['formPort'])){
				        $varPort = $_GET['formPort'];
				}else{
				        $varPort = 0;
				}
				echo "Now viewing data for port $varPort";
			?>
			<!-- Display the graphs -->
				<br>
				<br>
                                Every 15 minutes for the past 12 hours:
				<br>
				<img src="graph/15min12hr.php?port=<?=$varPort?>">
				<br>
				<br>
				Every 30 minutes for the past 24 hours:
				<br>
                                <img src="graph/30min24hr.php?port=<?=$varPort?>">
				<br>
				<br>
				Every 60 minutes for the past 48 hours:
				<br>
                                <img src="graph/60min48hr.php?port=<?=$varPort?>">
				<br>
				<br>
				Every Day for the past 30 days:
				<br>
                                <img src="graph/daily30day.php?port=<?=$varPort?>">
		</div>
	</body>
</html>
