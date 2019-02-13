<?php

include ('dbconnect.php');
require_once 'date.php';

getLastWeekData();

function getLastWeekData() {
    global $conn;

    $starting_day = getPreviousDays('7 days ago');
    $ending_day = getPreviousDays('1 day ago');
    $query = "SELECT * FROM rainfall WHERE date BETWEEN '$starting_day' AND '$ending_day' ORDER BY date";
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