<html>
        <head>
                <link rel="stylesheet" type="text/css" href="../css/wallboardstyle.css">
                <link rel="shortcut icon" href="../favicon.ico" />
		<title>Wallboard Graphs</title>
		<meta http-equiv="refresh" content="300">

	</head>
	<body>
	<h1>Service Connection Totals Every 30 minutes, for the Past 24 Hours.</h1>
	<!-- Display the graphs in 2 columns -->
	<div id="col1">
		<h2>HTTP</h2>
		<img src="30min24hr.php?port=80">
		<br>
		<h2>HTTPS</h2>
                <img src="30min24hr.php?port=443">
	</div>
	<div id="col2">
		<h2>SSH</h2>
                <img src="30min24hr.php?port=22">
		<br>
		<h2>SMTP</h2>
                <img src="30min24hr.php?port=25">
	</div>
	</body>
</html>
