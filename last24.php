<?php
include ('dbconnect.php');
include ('date.php');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>Compass Starter by Ariona, Rian</title>

		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
		<link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="styles/css/bundle.min.css">

		<!-- Loading main css file -->
		<link rel="stylesheet" href="style.css">

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
							<h1 class="site-title">University of Mauritius</h1>
							<small class="site-description">Urban Drainage Monitoring</small>
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
							<li class="menu-item"><a href="admin.html">Admin</a></li>
						</ul> <!-- .menu -->
					</div> <!-- .main-navigation -->

					<div class="mobile-navigation"></div>

				</div>
			</div> <!-- .site-header -->

			<main class="main-content">
				<div class="container">
					<div class="breadcrumb">
						<a href="index.html">Home</a>
						<span>Last 24 Hours</span>
					</div>
				</div>
                
				<div class="fullwidth-block">
					<div class="container">
						<div class="row">
							<div class="content col-md-8">
								<div class="post">
									<h2 class="entry-title">Drain 1</h2>
									<table>
										<tr>
                                            <th>Time(hour)</th>
											<th>Rainfall Intensity(mm)</th>
											<th>Depth(ft)</th>
											<th>Water Level(ft)</th>
										</tr>
                                    <?php
                                        getLastDay();
                        
                                        function getLastDay() {
											global $conn;
                                        
                                            $starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
                                            $ending_day = getCurrentDate(); // 30/01/2019 14:48
                                
											$query = "SELECT * FROM rainfall WHERE date BETWEEN '$starting_day' AND '$ending_day' AND drain_id = 1 ORDER BY forecast_time";
                                            $result = mysqli_query($conn, $query);
                                            
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
													?>
													<tr>
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

								<div class="post">
									<h2 class="entry-title">Drain 2</h2>
									<table>
											<tr>
												<th>Time(hour)</td>
												<th>Rainfall Intensity(mm)</td>
												<th>Depth(ft)</td>
												<th>Water Level(ft)</td>
											</tr>
											<tr>
												<td>x</td>
												<td>x</td>
												<td>x</td>
												<td>x</td>
											</tr>
											</table>
								</div>

								<div class="post">
									<h2 class="entry-title">Drain 3</h2>
									<table>
											<tr>
												<th>Time(hour)</td>
												<th>Rainfall Intensity(mm)</td>
												<th>Depth(ft)</td>
												<th>Water Level(ft)</td>
											</tr>
											<tr>
												<td>x</td>
												<td>x</td>
												<td>x</td>
												<td>x</td>
											</tr>
											</table>
								</div>
							</div>
							<!-- Calendar -->
							 <div class="sidebar col-md-3 col-md-offset-1">
								<div id='calendar-wrap' class='calendar-wrap'></div>
							</div> 
						</div>
					</div>
				</div>
			</main> <!-- .main-content -->

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
									<h2 class="section-title" id="history">Quick Links</h2>
									<ul class="arrow-list">
										<li><a href="last24.html">Last 24 Hours Water Level in Drain 1, Drain 2, Drain 3</a></li>
										<li><a href="#">Last week Water Level in Drain 1, Drain 2, Drain 3</a></li>
										<li><a href="#">Nemo enim ipsam voluptatem quia voluptas</a></li>
										<li><a href="#">Aspernatur aut odit aut fugit, sed quia consequuntur</a></li>
										<li><a href="#">Magni dolores eos qui ratione voluptatem sequi</a></li>
										<li><a href="#">Neque porro quisquam est qui dolorem ipsum quia</a></li>
									</ul>
								</div>-->
							</div> 
		
						<p class="colophon">Copyright 2014 Company name. Designed by Themezy. All rights reserved</p>
					</div>
				</footer> <!-- .site-footer -->
		</div>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/app.js"></script>
		<script src="js/calendar.js"></script>
		<script src="js/index.js"></script>
	</body>
</html>