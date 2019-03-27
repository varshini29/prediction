<?php

include ('dbconnect.php');

calcWLDB2();

function calcWLDB2() {
    for ($i = 1; $i < 4; $i++) {
        $previousRI = fetchPreviousForecast($i)[0]->precipitation;
        $nextHourData = fetchWaterLevels($i);
        //print_r($nextHourData);
        $previousWL = fetchPreviousForecast($i)[0]->water_level;
        
            if ($nextHourData[0]->precipitation == $previousRI) {
                print_r($previousWL);
                //echo 'Same';
                echo "<br/>";
                updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else if ($nextHourData[0]->precipitation > $previousRI) {
               $previousWL += $nextHourData[0]->water_level;
               if ($previousWL < 0) {
                   $previousWL = 0;
               }
               //echo "Testing addition for the first value";
               print_r($previousWL);
               echo "<br/>";
               updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else if ($nextHourData[0]->precipitation < $previousRI) {
                $previousWL -= $nextHourData[0]->water_level;
                if ($previousWL < 0) {
                    $previousWL = 0;
                }
                print_r($previousWL);
                echo "<br/>";
                updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else {
                //echo "No operation performed!";
            }
           
            for($k = 1; $k < count($nextHourData); $k++) {
                if ($nextHourData[$k]->precipitation == $nextHourData[$k-1]->precipitation) {
                    print_r($previousWL);
                  //  echo 'Same';
                    echo "<br/>";
                    updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else if ($nextHourData[$k]->precipitation > $nextHourData[$k-1]->precipitation) {
                $previousWL += $nextHourData[$k]->water_level;
                if ($previousWL < 0) {
                    $previousWL = 0;
                }
                //echo "Testing addition in the for loop " . $previousWL;
                print_r($previousWL);
                echo "<br/>";
                updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else if ($nextHourData[$k]->precipitation < $nextHourData[$k-1]->precipitation) {
                    $previousWL -= $nextHourData[$k]->water_level;
                    if ($previousWL < 0) {
                        $previousWL = 0;
                    }
                  //  echo "Testing addition in the for loop " . $previousWL;
                  print_r($previousWL);  
                  echo "<br/>";
                  updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else {
                //    echo "No operation performed!";
                }
            }
     
    }
    
}


function formatForecastTime($time) {
    $res = date('H:i', strtotime($time . '-1 hour'));
    return $res;
}



function updateActualWL($waterLevel, $ftime, $drainid) {
    global $conn;
    $sql = "UPDATE rainfall SET actual_water_level = '$waterLevel' WHERE forecast_time = '$ftime' AND status = 'active' AND drain_id = '$drainid'";
    mysqli_query($conn, $sql);
}


function getCurrentDate() {
    $now = new DateTime('-1 hour', new DateTimeZone('Indian/Mauritius'));
    return $now->format('d/m/Y H:i');
}


function fetchPreviousForecast($drainid) {
    global $conn;
    $arrayPreviousData = array();
    $first_fc = formatForecastTime(fetchWaterLevels($drainid)[0]->time);
    $query = "SELECT * FROM rainfall WHERE forecast_time = '$first_fc' AND drain_id = '$drainid' ORDER BY rain_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $object = (object) [
                'time' => $row['forecast_time'],
                'precipitation' => $row['rainfall_intensity'],
                'water_level' => $row['water_level'],
                'date' => $row['date']
            ];
            array_push($arrayPreviousData, $object);
        }
    }
    else {
        echo 'No data';
    }
   return $arrayPreviousData;
}

function fetchWaterLevels($drainid) {
    global $conn;
    $dataFromDB = array();
     $query = "SELECT * FROM rainfall WHERE status = 'active' AND drain_id = '$drainid'";
     $result = mysqli_query($conn, $query);
     if (mysqli_num_rows($result) > 0) {
         while ($row = mysqli_fetch_assoc($result)) {
            $object = (object) [
                'time' => $row['forecast_time'],
                'precipitation' => $row['rainfall_intensity'],
                'water_level' => $row['water_level'],
                'date' => $row['date']
            ];
            array_push($dataFromDB, $object);
         }
     }
     else {
         echo 'No data';
     }
    return $dataFromDB;
}


?>