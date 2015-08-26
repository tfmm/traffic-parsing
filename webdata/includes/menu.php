<html>
	<head>
                <link rel="stylesheet" type="text/css" href="css/menu.css">
        </head>
	<body>
                        <nav>
			<!--Links for the menu, there are multiple levels, as some drop-down under others -->
                        <ul>
                                <li><a href="index.php">Home</a></li>
                                <li><a href="topsources.php">Data by Source IP</a>
					<ul>
                                        <li><a href="destinationips.php">Destinations by Source IP</a></li>
                                        <li><a href="search.php">Search by Source IP</a></li>
					<li><a href="topsources.php">Top Traffic by Source</a></li>
	                                <li><a href="whoislookup.php">WhoIS Search</a></li>
					</ul>
				</li>
				<li><a href="topdestinations.php">Data by Destination IP</a>
					<ul>
                                        <li><a href="sourceips.php">Sources by Destination IP</a></li>
					<li><a href="topdestinations.php">Top Traffic by Destination</a></li>
				</ul>
				</li>
	                        <li><a href=mapping.php>Traffic Visuals</a>
                                        <ul>
                                                <li><a href="graphs.php">Traffic Graphs</a></li>
                                                <li><a href="mapping.php">Traffic Map</a></li>
                                        </ul>
                                </li>
                        </ul>
                        </nav>
	</body>
</html>
