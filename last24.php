<?php
include ('dbconnect.php');
include ('date.php');

/**
 * @description Getting last 24 hours data from MySQL based on drain id
 * @param int $drainid
 * @return array $result
 */
function getData($drainid) {
	global $conn;
	$starting_day = getPreviousDays('1 day ago');
	$ending_day = getCurrentDate();
	$sql = "SELECT rainfall_intensity, forecast_time, water_level, date FROM rainfall WHERE date BETWEEN '$starting_day'AND '$ending_day' AND status = 'inactive'AND drain_id = $drainid ORDER BY forecast_time ";
	$result = mysqli_query($conn, $sql);
	return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Historical Data</title>
		<link rel="shortcut icon" href="images/logo.png">
		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="styles/css/bundle.min.css">
		<link rel="stylesheet" href="styles/css/custom.css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="styles/css/custom.css">

		<!-- Loading third party JS -->
		<script type="text/javascript" src="js/Chart.bundle.min.js"></script>
		
		<script>
			/**
			 * @description Chartjs
			 * @param string chart (The id of the chart you are referencing to)
			 * @param array times
			 * @param array rainfalls
			 * @param array waterlevels
			 * @return chart
 			*/
			function generateChart(chart, times, rainfalls, waterlevels) {
				var ctx = document.getElementById(chart).getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: times,
						datasets: [
						{
							label: 'Rainfall Intensity',
							data: rainfalls,
							backgroundColor: 'transparent',
							borderColor:'rgba(255,99,132)',
							borderWidth: 3
						},
						{
							label: 'Water Level',
							data: waterlevels,
							backgroundColor: 'transparent',
							borderColor:'rgba(0,255,255)',
							borderWidth: 3
						}
						]
					},
					options: {
						scales: {scales: { 
							yAxes: [{ beginAtZero: false }],
							xAxes: [{ autoskip: true, maxTicketsLimit: 20 }]
						}},
						tooltips: { mode: 'index' },
						responsive: true,
						legend: { display: true, position: 'top', labels: { fontColor: 'rgb(255,255,255)', fontSize: 16 } }
					}
				})
				return myChart;
			};
		</script>

		<!-- Custom styles --> 
		<style>
			.calendar-wrap {
				margin-top: 0;	
			}
		</style>
		
		<!--[if lt IE 9]>
		<script src="js/ie-support/html5.js"></script>
		<script src="js/ie-support/respond.js"></script>
		<![endif]-->

	</head>


	<body>
		
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
							<li class="menu-item"><a href="drain1.php">Drain 1</a></li>
							<li class="menu-item"><a href="drain2.php">Drain 2</a></li>
							<li class="menu-item"><a href="drain3.php">Drain 3</a></li>
							<li class="menu-item  current-menu-item"><a href="">History Data</a></li>
							<li class="menu-item"><a href="flood.html">Flood Case Scenario</a></li>
						</ul> <!-- .menu -->
					</div> <!-- .main-navigation -->

					<div class="mobile-navigation"></div>

				</div>
			</div> <!-- .site-header -->

			<main id="last24" class="main-content">
					<div class="container">
						<div class="row">
							<div class="col-lg-12 p-0">
								<h1 id="__history-heading">Last 24 Hours Data</h1>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 p-0">
								<div class="banner-wrapper">
									<h2>Historical Data</h2>
									<p id="__banner-text">Past history information are displayed. To view older history, click on the date you want to see in the calendar.</p>
								</div>
							</div>
							<div class="col-lg-6">
								<div id='calendar-wrap' class='calendar-wrap'></div>
							</div>
						</div>
						<div class="row">
							<div class="content col-lg-12 p-0">
								<div class="row w-100 ml-0" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 0 0 25px 0;">
									<h2 class="entry-title w-100">Drain 1</h2>
									<div class="post col-lg-6 p-0 border-none">
										<table id="t-d1" class="w-auto table table-bordered text-white custom-bg-table">
											<?php
												getDrain1LastDay();
							
												function getDrain1LastDay() {
													global $conn;
											
													$starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
													$ending_day = getCurrentDate(); // 30/01/2019 14:48
											?>
											<thead>
												<tr class="day">
													<th>Time(hour)</th>
													<th>Rainfall Intensity(mm)</th>
													<th>Depth(ft)</th>
													<th>Water Level(ft)</th>
												</tr>
											</thead>
											<?php
								
											$query = "SELECT * FROM rainfall WHERE date BETWEEN '$starting_day' AND '$ending_day' AND drain_id = 1 AND status = 'inactive'";
											$result = mysqli_query($conn, $query);
											//print_r($result);
											
											if (mysqli_num_rows($result) > 0) {
												while ($row = mysqli_fetch_assoc($result)) {
											?>
											<tr class="num" style="font-size:15px; color:white;">
												<td><?php echo $row['forecast_time']?></td>
												<td><?php echo $row['rainfall_intensity']?></td>
												<td>3</td>
												<td><?php echo $row['water_level']?></td>
											</tr>
	
											<?php	}
											} else {
												echo 'No result!';
											}
										}
											?>
										</table>
									</div>
									<div class="post col-lg-6 p-0 border-none">
										<div class="position-relative">
											<canvas id="chart-drain1" style="background: #222; border: 1px solid #555652;"></canvas>
										</div>
									<?php 
			
										$rainfall = '';
										$time = '';
										$waterlevel='';

										$result = getData(1);

										while ($row = mysqli_fetch_array($result)) {
											$rainfall = $rainfall . '"'. $row['rainfall_intensity'].'",';
											$time = $time . '"'. $row['forecast_time'] .'",';
											$waterlevel = $waterlevel . '"'. $row['water_level'] .'",';
										} 
									?>
									
									<script>
										generateChart('chart-drain1', [<?php echo $time; ?>], [<?php echo $rainfall; ?>], [<?php echo $waterlevel; ?>]);
									</script>
									
									</div>
								</div>

								<div class="row w-100 ml-0" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 25px 0 25px 0;">
									<h2 class="w-100 entry-title">Drain 2</h2>
									<div class="post col-lg-6 p-0 border-none">
										<table id="t-d2" class="w-auto table table-bordered text-white custom-bg-table">
											<?php
												getDrain2LastDay();
							
												function getDrain2LastDay() {
													global $conn;
											
													$starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
													$ending_day = getCurrentDate(); // 30/01/2019 14:48
											?>
											<thead>
												<tr class="day">
													<th>Time(hour)</th>
													<th>Rainfall Intensity(mm)</th>
													<th>Depth(ft)</th>
													<th>Water Level(ft)</th>
												</tr>
											</thead>
											<?php
								
											$query = "SELECT * FROM rainfall WHERE date BETWEEN '$starting_day' AND '$ending_day' AND drain_id = 2 AND status = 'inactive'";
											$result = mysqli_query($conn, $query);
											
											if (mysqli_num_rows($result) > 0) {
												while ($row = mysqli_fetch_assoc($result)) {
											?>
											<tr class="num" style="font-size:15px; color:white;">
												<td><?php echo $row['forecast_time']?></td>
												<td><?php echo $row['rainfall_intensity']?></td>
												<td>7.22</td>
												<td><?php echo $row['water_level']?></td>
											</tr>
											<?php	}
											} else {
												echo 'No result!';
											}
												}
											?>
										</table>
									</div>
									
									<div class="post col-lg-6 p-0 border-none">
										<div class="position-relative">
											<canvas id="chart-drain2" style="background: #222; border: 1px solid #555652;"></canvas>
										</div>
									<?php 
			
										$rainfall = '';
										$time = '';
										$waterlevel='';

										$result = getData(2);

										while ($row = mysqli_fetch_array($result)) {
											$rainfall = $rainfall . '"'. $row['rainfall_intensity'].'",';
											$time = $time . '"'. $row['forecast_time'] .'",';
											$waterlevel = $waterlevel . '"'. $row['water_level'] .'",';
										} 
									?>
									
									<script>
										generateChart('chart-drain2', [<?php echo $time; ?>], [<?php echo $rainfall; ?>], [<?php echo $waterlevel; ?>]);
									</script>
									
									</div>

								</div>

								<div class="row w-100 ml-0" style="padding: 25px 0 25px 0;">
									<h2 class="w-100 entry-title">Drain 3</h2>
									<div class="post col-lg-6 p-0 border-none">
										<table id="t-d3" class="w-auto table table-bordered text-white custom-bg-table">
											<?php
												getDrain3LastDay();
							
												function getDrain3LastDay() {
													global $conn;
											
													$starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
													$ending_day = getCurrentDate(); // 30/01/2019 14:48
											?>
											<thead>
												<tr class="day">
													<th>Time(hour)</th>
													<th>Rainfall Intensity(mm)</th>
													<th>Depth(ft)</th>
													<th>Water Level(ft)</th>
												</tr>
											</thead>
											<?php
								
											$query = "SELECT * FROM rainfall WHERE date BETWEEN '$starting_day' AND '$ending_day' AND drain_id = 3 AND status = 'inactive'";
											$result = mysqli_query($conn, $query);
											
											if (mysqli_num_rows($result) > 0) {
												while ($row = mysqli_fetch_assoc($result)) {
											?>
											<tr class="num" style="font-size:15px; color:white;">
												<td><?php echo $row['forecast_time']?></td>
												<td><?php echo $row['rainfall_intensity']?></td>
												<td>4.79</td>
												<td><?php echo $row['water_level']?></td>
											</tr>
											<?php	}
											} else {
												echo 'No result!';
											}
												}
											?>
										</table>
									</div>
									
									<div class="post col-lg-6 p-0 border-none">
										<div class="position-relative">
											<canvas id="chart-drain3" style="background: #222; border: 1px solid #555652;"></canvas>
										</div>
									<?php 
			
										$rainfall = '';
										$time = '';
										$waterlevel='';

										$result = getData(3);

										while ($row = mysqli_fetch_array($result)) {
											$rainfall = $rainfall . '"'. $row['rainfall_intensity'].'",';
											$time = $time . '"'. $row['forecast_time'] .'",';
											$waterlevel = $waterlevel . '"'. $row['water_level'] .'",';
										} 
									?>
									
									<script>
										generateChart('chart-drain3', [<?php echo $time; ?>], [<?php echo $rainfall; ?>], [<?php echo $waterlevel; ?>]);
									</script>
									
									</div>

								</div>

								
							</div>
						</div>
						</div>
					</div>
				
			</main> <!-- .main-content -->

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
		<script type="text/javascript" src="js/app.js"></script>
		<script src="js/calendar.js"></script>
		<script src="js/index.js"></script>
	</body>
</html>