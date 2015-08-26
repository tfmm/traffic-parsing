<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="shortcut icon" href="favicon.ico" />
		<title>WhoIS Lookup</title>
	</head>
	<body>
		<div class=header>
                        <h2>Traffic Monitoring</h2>
                        <h3>IP WhoIs Lookup</h3>
	                <?php include("includes/menu.php"); ?>
		</div>
		<div class=content>
		<!-- IP Entry form -->
			<form>
				<p>
					Please enter the IP address for which you wish to search:
					<br>
					<input type="text" name="formIPaddr">
					<br>
					<input name="formSubmit" type="submit" value="Search">
				</p>
			</form>
		</div>
		<div class=whois>
			<?php
				if(isset($_GET['formIPaddr'])) {
					//The filter statement validates input as an IP address only, not allowing lookups of other data.
					$varIPaddr = filter_var(trim($_GET['formIPaddr']), FILTER_VALIDATE_IP);
				//use the Pear Net::Whois class to perform the WhoIs lookup
				include("Net/Whois.php");
				$whois = new Net_Whois();
				$result = $whois->query($varIPaddr);
				//Display the results
				echo "<br><pre>".$result."</pre>";
				}
				else
				//If nothing is entered, send error message
				echo "Please enter an IP Address"
				?>
		</div>
	</body>
</html>
