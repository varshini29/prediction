<?php
    
    include ('dbconnect.php');
    global $conn;

    // Request
    $param = $_POST['arguments'];

    // Reply
    //echo $param[0];
    $query = "SELECT * FROM rainfall WHERE date LIKE '%$param[0]%' ";
    $result = mysqli_query($conn ,$query);

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
    

?>