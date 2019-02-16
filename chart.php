<?php
	include ('dbconnect.php');
	include ('date.php');
	
	
	 getLastDay();
                        
    function getLastDay() {
		$rainfall = '';
		$time = '';
		$waterlevel='';
		global $conn;
                                        
        $starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
        $ending_day = getCurrentDate(); // 30/01/2019 14:48
	//query to get data from the table
	$sql = "SELECT rainfall_intensity, forecast_time, water_level FROM rainfall WHERE date BETWEEN '$starting_day'AND '$ending_day' AND status = 'inactive'AND drain_id = 1 ORDER BY forecast_time ";
    $result = mysqli_query($conn, $sql);

	//loop through the returned data
	while ($row = mysqli_fetch_array($result)) {

		$rainfall = $rainfall . '"'. $row['rainfall_intensity'].'",';
		$time = $time . '"'. $row['forecast_time'] .'",';
		$waterlevel = $waterlevel . '"'. $row['water_level'] .'",';
	}

	$rainfall = trim($rainfall,",");
	$time = trim($time,",");
	$waterlevel = trim($waterlevel,",");
}
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="js/Chart.bundle.min.js"></script>
		<title>Accelerometer data</title>

		<style type="text/css">			
			body{
				font-family: Arial;
			    margin: 80px 100px 10px 100px;
			    padding: 0;
			    color: white;
			    text-align: center;
			    background: #555652;
			}

			.container {
				color: #E8E9EB;
				background: #222;
				border: #555652 1px solid;
				padding: 10px;
			}
		</style>

	</head>

	<body>	   
	    <div class="container">	
       
			<canvas id="chart" style="width: 500px; height: 50px; background: #222; border: 1px solid #555652; margin-top: 10px;"></canvas>

			<script>
				var ctx = document.getElementById("chart").getContext('2d');
    			var myChart = new Chart(ctx, {
        		type: 'line',
		        data: {
		            labels: [<?php echo $time; ?>],
		            datasets: 
		            [{
		                label: 'rainfall intensity',
		                data: [<?php echo $rainfall; ?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(255,99,132)',
		                borderWidth: 3
		            },

		            {
		            	label: 'water level',
		                data: [<?php echo $waterlevel; ?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(0,255,255)',
		                borderWidth: 3	
					}
				]
		        },
		     
		        options: {
		            scales: {scales:{yAxes: [{beginAtZero: false}], xAxes: [{autoskip: true, maxTicketsLimit: 20}]}},
		            tooltips:{mode: 'index'},
		            legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
		        }
		    });
			</script>
	    </div>
	    
	</body>
</html>