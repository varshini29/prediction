<?php
include ("dbconnect.php");
	
?>
<!DOCTYPE html>
<html lang="en">
<s
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Urban Drainage Monitoring</title>

		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">

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
<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
}
</style>
	</head>


	<body onload="displaydate()">
		
		<div class="site-content">
			<div class="site-header">
				<div class="container">
					<a href="index.html" class="branding">
						<img src="images/logo.png" alt="" class="logo">
						<div class="logo-type">
							<h1 class="site-title">University of Mauritius</h1>
							<small class="site-description">Urban Drainage Monitoring</small>
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
							<li class="menu-item"><a href="last24.html">History Data</a></li>
							<li class="menu-item"><a href="admin.html">Admin</a></li>
							</ul> <!-- .menu -->
						</div> <!-- .main-navigation -->
					</div>
				</div> <!-- .site-header -->

			<!--map to put here-->
			<div><iframe class="center" src="https://www.google.com/maps/embed?pb=!1m24!1m12!1m3!1d3745.173807243324!2d57.498565640978455!3d-20.168487508278744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m9!3e0!4m3!3m2!1d-20.1669678!2d57.4988518!4m3!3m2!1d-20.1706009!2d57.5027705!5e0!3m2!1sen!2smu!4v1550317178644" width="1000" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>
			
			<h2 style="padding:100px;color:white;font-size:32px;">Drain 1 Water Level Forecast</h2>
				<main class="main-content">
				<div class="fullwidth-block">
                    <div class="forecast-table">
					<div class="container">
						<div class="forecast-container">
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
							<div class="today forecast"style="width:300px;">
								<div class="forecast-header">
									<div class="day">Time (in hour)</div>
									<div class="date" id="" style="text-align: center"></div><!--this is for display date and time will need this-->
								</div>
									<div class="degree">
										<div class="num" style="font-size:15px; color:white;" id="">
											<?php
												
												foreach($data as $object) {
													echo "<div>" . $object["forecast_time"] . "</div>";
												}
								
											?>
										</div>
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
								<div class="forecast-content">
									<span id="depth"></span></br>
										<script>
										var i;
										var text="";
										for(i=0;i<4;i++){
											text += 3.0 + "<br>";
										}
										document.getElementById("depth").innerHTML = text;
										</script>
							
								</div>
							</div>
							<div class="forecast">
								<div class="forecast-header">
									<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Drainage Capacity(cfs)</div>
								</div> <!-- .forecast-header -->
								<div class="forecast-content">
									<span id="cap"></span></br>
										<script>
										var i;
										var text="";
										for(i=0;i<4;i++){
											text += 64.9 + "<br>";
										}
										document.getElementById("cap").innerHTML = text;
										</script>
							
								</div>
							</div>

							<div class="forecast">
								<div class="forecast-header">
									<div class="day" style="font-weight: bold;color:white;font-size: 15px;">Water Level(ft)</div>
								</div> <!-- .forecast-header-->
								<div class="forecast-content">
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
				
<div class="row">
  <div class="column">
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/687423/charts/1?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=WaterFlow&type=line"></iframe>

  </div>
  <div class="column">
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/687423/charts/2?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=WaterFlow&type=line"></iframe>

  </div>
  <div class="column">
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/687423/charts/3?bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=WaterFlow&type=line"></iframe>

  </div>
</div>
				
			</main> 

			<footer class="site-footer">
					<div class="container">
						<div class="row">
								<div class="col-md-6 col-md-offset-1">
										<h2 class="section-title">Contact us</h2>
										
										<form action="#" class="contact-form">
											<div class="row">
												<div class="col-md-6"><input type="text" placeholder="Your name..."></div>
												<div class="col-md-6"><input type="text" placeholder="Email Addresss..."></div>
											</div>
											
											<textarea name="" placeholder="Message..."></textarea>
											<div class="text-right">
												<input type="submit" placeholder="Send message">
											</div>
										</form>
			
									</div>
							
							<!-- <div class="col-md-4">
									<h2 class="section-title">Quick Links</h2>
									<ul class="arrow-list">

										<li><a href="last24.html">Last 24 Hours Water Level in Drain 1, Drain 2, Drain 3</a></li>
										<li><a href="#">Last week Water Level in Drain 1</a></li>
										<li><a href="#">Last week Water Level in Drain 2</a></li>
										<li><a href="#">Last week Water Level in Drain 3</a></li>
										<li><a href="#">Magni dolores eos qui ratione voluptatem sequi</a></li>
										<li><a href="#">Neque porro quisquam est qui dolorem ipsum quia</a></li>
									</ul>
								</div> -->
							</div>
					<p class="colophon">Copyright 2014 Company name. Designed by Themezy. All rights reserved</p>
				</div>
			</footer> <!-- .site-footer -->
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		
	</body>

</html>