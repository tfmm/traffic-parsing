<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/wallboardstyle.css">
		<link rel="shortcut icon" href="../favicon.ico" />
		<script type='text/javascript' src='../mapdata/jquery-1.6.2.min.js'></script>
		<script type='text/javascript' src='../mapdata/jquery-ui.min.js'></script>
		<script type='text/javascript' src='../mapdata/mcplus/src/markerclusterer_packed.js'></script>
		<title>Wallboard Map</title>
		<?php
			$varPort2 = $_GET['formPort'];
			if($varPort2 != ""){
			        echo "<meta http-equiv=\"refresh\" content=\"60; map.php?formPort=" . $varPort2. "\">";
			        }
			?>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false" /></script>
		<!-- Javascript to plot the locations of Source IPs on the map -->
		<script type='text/javascript'>
			//This javascript will load when the page loads.
			jQuery(document).ready( function($){
			
			        //Initialize the Google Maps
			        var geocoder;
			        var map;
			        var markersArray = [];
			        var infos = [];
			
			        geocoder = new google.maps.Geocoder();
			        var myOptions = {
			              zoom: 2,
			              mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: new google.maps.LatLng(13.30410, -27.570313),
			            }
			        //Load the Map into the map_canvas div
			        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			        //map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
				var mcOptions = {gridSize: 50, maxZoom: 14};
			        //Initialize a variable that the auto-size the map to whatever you are plotting
			        var bounds = new google.maps.LatLngBounds();
			        //Initialize the encoded string
			        var encodedString;
			        //Initialize the array that will hold the contents of the split string
			        var stringArray = [];
			        //Get the value of the encoded string from the hidden input
			        encodedString = document.getElementById("encodedString").value;
			        //Split the encoded string into an array the separates each location
			        stringArray = encodedString.split("****");
			
			        var x;
			        for (x = 0; x < stringArray.length; x = x + 1)
			        {
			            var addressDetails = [];
			            var marker;
			            //Separate each field
			            addressDetails = stringArray[x].split("&&&");
			            //Load the lat, long data
			            var lat = new google.maps.LatLng(addressDetails[1], addressDetails[2]);
			            //Create a new marker and info window
			            marker = new google.maps.Marker({
			                map: map,
			                position: lat,
			                //Content is what will show up in the info window
			                content: addressDetails[0]
			            });
			            //Pushing the markers into an array so that it's easier to manage them
			            markersArray.push(marker);
			            google.maps.event.addListener( marker, 'click', function () {
			                closeInfos();
			                var info = new google.maps.InfoWindow({content: this.content});
			                //On click the map will load the info window
			                info.open(map,this);
			                infos[0]=info;
			            });
			           //Extends the boundaries of the map to include this new location
			           bounds.extend(lat);
			        }
			        //Takes all the lat, longs in the bounds variable and autosizes the map
			        //map.fitBounds(bounds);
                                var mc = new MarkerClusterer(map, markersArray, mcOptions);	
			        //Manages the info windows
			        function closeInfos(){
			       if(infos.length > 0){
			          infos[0].set("marker",null);
			          infos[0].close();
			          infos.length = 0;
			       }
			        }
			});
		</script>
	</head>
	<body>
	<!-- Perform the MySQL query to get the source IPs, and geolocate them using the Pear Net::GeoIP class -->
		<?php
			require_once "Net/GeoIP.php";
			$geoip = Net_GeoIP::getInstance("../mapdata/GeoLiteCity.dat");
			
			//Get data from the form submit
			if(isset($_GET['formPort'])){
			        $varPort = $_GET['formPort'];
			}else{
			        $varPort = 0;
			}
			
			//Connect to DB to pull IPs
			//Include DB connection Info
			include("../includes/db_config.php");
			
			// Create connection
			$conn1 = new mysqli($servername, $username, $password, $dbname);
			
			//Create dropdown
			$query1 = "SELECT src_ip FROM rawdata WHERE `dst_port` = '".$varPort."' AND time > (UNIX_TIMESTAMP() - 900) GROUP BY src_ip";
			$iplist = mysqli_query($conn1, $query1);
			
			echo "<h2>Locations of the Unique Source IP addresses for incoming traffic on Port $varPort from the last 15 minutes</h2>";
			
			        //Initialize your first couple variables
			        $encodedString = ""; //This is the string that will hold all your location data
			        $x = 0; //This is a trigger to keep the string tidy
			
			
			        //Multiple rows are returned
			        while ($row = mysqli_fetch_array($iplist))
			        {
					$ip = $row['src_ip'];
					$location = $geoip->lookupLocation($ip);
			            //This is to keep an empty first or last line from forming, when the string is split
			            if ( $x == 0 )
			            {
			                 $separator = "";
			            }
			            else
			            {
			                 //Each row in the database is separated in the string by four *'s
			                 $separator = "****";
			            }
			            //Saving to the String, each variable is separated by three &'s
			            $encodedString = $encodedString.$separator.
			            "<p class='content'><b>IP: </b>".$row['src_ip'].
				    "<br><b>Lat:</b> ".$location->latitude.
			            "<br><b>Long:</b> ".$location->longitude.
			            "</p>&&&".$location->latitude."&&&".$location->longitude;
			            $x = $x + 1;
			        }
			$conn1->close();
			        ?>
		<input type="hidden" id="encodedString" name="encodedString" value="<?php echo $encodedString; ?>" />
		</div>
		<!-- Display the map -->
		<div id="map_canvas"></div>
	</body>
</html>
