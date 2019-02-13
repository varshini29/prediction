<?php

include ('dbconnect.php');
include ('date.php');

getLastDay();

function getLastDay() {
    global $conn;

    $starting_day = getPreviousDays('1 day ago'); // 29/01/2019 14:48
    //$ending_day = getCurrentDate(); // 30/01/2019 14:48
    print_r($starting_day);

    $query = "SELECT * FROM rainfall WHERE date='$starting_day' ORDER BY date";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Time: " . $row['forecast_time'] . "<br/>";
            echo "Precipitation: " . $row['rainfall_intensity']."mm" . "<br/>";
            echo "Date: " . $row['date'] . "<br/>";
            echo "<br/>";
        }
    } else {
        echo 'No data!';
    }
}

?>
