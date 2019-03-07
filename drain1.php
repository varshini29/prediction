<?php
include ("dbconnect.php");
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Drain 1</title>
		<link rel="shortcut icon" href="images/logo.png">
		<!-- Loading third party fonts -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">

		<link rel="stylesheet" href="styles/css/custom.css">

		<script type="text/javascript"> 
		function refreshtime(){
			var refresh=1000; // Refresh rate in milli seconds
			mytime=setTimeout('displaydate()',refresh)
		}
		function displaydate() {
			var x = new Date()
			console.log("date is " + x);
			//var x1=x.toUTCString();
			
			
			var x1=x.getHours() + 1;
			console.log("get hours " + x1);
			
			maxhours = x1+4;
			var result = "";
			var finalvalue = "";
			for(var i=x1;i<maxhours;i++){
				result += i + "</br>"
			}
			document.getElementById('ct').innerHTML = result;
 				}
		</script>

	</head>


	<body onload="displaydate()">
		<div class="site-content">
			<div class="site-header">
				<div class="container">
					<a href="index.html" class="branding">
						<img src="images/logo.png" alt="" class="logo">
						<div class="logo-type">
							<h1 class="site-title">Urban Drainage Monitoring</h1>
							<small class="site-description">University of Mauritius</small>
						</div>
					</a>

					<!-- Default snippet for navigation -->
					<div class="main-navigation">
						<button type="button" class="menu-toggle"><i class="fa fa-bars"></i></button>
						<ul class="menu">
							<li class="menu-item"><a href="index.html">Home</a></li>
							<li class="menu-item current-menu-item"><a href="drain1.php">Drain1</a></li>
							<li class="menu-item"><a href="drain2.php">Drain 2</a></li>
							<li class="menu-item"><a href="drain3.php">Drain 3</a></li>
							<li class="menu-item"><a href="last24.php">History Data</a></li>
							<li class="menu-item"><a href="flood.html">Flood Case Scenario</a></li>
						</ul> <!-- .menu -->
					</div> <!-- .main-navigation -->
				</div>
			</div> <!-- .site-header -->

			<!--map to put here-->
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="hero m-0 w-100" data-bg-image="images/drain1.png"></div>
					</div>
				</div>
			</div>
			
			<div class="container">
				<div class="col-lg-12 py-4 px-0">
					<h2 class="m-0 table-h-custom">Drain 1 Water Level Forecast</h2>
				</div>
			</div>

			<section id="drain1">
				<div class="container pb-4">
					<div class="row">
						<div class="col-lg-12">
							<div class="forecast-table">
								<div class="forecast-container m-0">
									<?php
										$sql = "SELECT * FROM rainfall WHERE status = 'active' AND drain_id = 1 ORDER BY forecast_time ";
										$result = mysqli_query($conn, $sql);
	
										$data = array();
										if (mysqli_num_rows($result) > 0) {
											while($row = mysqli_fetch_assoc($result)) {
												//$dat = $row;
												array_push($data, $row);
											}
	
									?>
	
										<div class="forecast">
											<div class="forecast-header">
											<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Time (in hour)</div>
											<div class="date" id="" style="text-align: center"></div><!--this is for display date and time will need this-->
										</div>
										<div class="forecast-content">
												<?php
													
													foreach($data as $object) {
														echo "<div>" . $object["forecast_time"] . "</div>";
													}
									
												?>
										</div>
									</div>
	
									<div class="forecast">
										<div class="forecast-header">
											<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Rainfall Intensity(mm)</div>
										</div> 
										<div class="forecast-content">
										<?php
												
										foreach($data as $object) {
											echo "<div>" . $object["rainfall_intensity"] . "</div>";
										}
						
										?>
										</div>
									</div>
	
	
									<div class="forecast">
										<div class="forecast-header">
											<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Depth(ft)</div>
										</div> <!-- .forecast-header -->
										<div id="depth-forecast" class="forecast-content">
											<script>
												for(var i = 0; i < 4; i++) {
													document.getElementById("depth-forecast").innerHTML += '<div>3.0</div>';
												}
											</script>
										</div>
									</div>
	
									<div class="forecast">
										<div class="forecast-header">
											<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Drainage Capacity(cfs)</div>
										</div> <!-- .forecast-header -->
										<div id="drainage-cap-forecast" class="forecast-content">
										<script>
												for(var i = 0; i < 4; i++) {
													document.getElementById("drainage-cap-forecast").innerHTML += '<div>64.9</div>';
												}
											</script>
										</div>
									</div>
	
									<div class="forecast">
										<div class="forecast-header">
											<div class="font-drain">Forecast Water Level Raise By(ft)</div>
										</div> <!-- .forecast-header-->
										<div class="forecast-content" style="color:#48c0f0;font-weight: bold;">
										<?php
												foreach($data as $object) {
													echo "<div>" . $object["water_level"] . "</div>";
												}
											}
								
												?>
										</div>
									</div>
	
								</div>
							</div>
						</div>
					</div>
					<p class="mt-4 mb-0">The table above do 4 hours prediction of water level in feet based on rainfall intensity in mm.  Drainage capacity  indicates the amount of liquid that can be discharged at the open channel end. </p>
				</div>
			</section>
				

			<footer class="site-footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 my-2">
						<p class="m-0 text-center">&copy; 2019 University of Mauritius. All rights reserved</p>
					</div>
				</div>
			</div>
			</footer> <!-- .site-footer -->
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		
	</body>

</html>