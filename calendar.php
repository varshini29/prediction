<?php
    
    include ('dbconnect.php');
    global $conn;

    $param = $_POST['arguments'];
    $output = [];

    $query = "SELECT * FROM rainfall WHERE date LIKE '%$param[0]%' ";
    $result = mysqli_query($conn ,$query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($output, $row);
        }
    } else {
        echo 'No data!';
    }

    echo json_encode($output);
    
?>