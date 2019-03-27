<?php

include ('dbconnect.php');

/**
 * Calculating next actual water level based on the previous water level and rainfall intensity
 * @return void
 */
function calculateActualWL() {
    for ($i = 1; $i < 4; $i++) {
        $previousRI = fetchPreviousForecast($i)[0]->precipitation;
        $nextHourData = fetchWaterLevels($i);
        $previousWL = fetchPreviousForecast($i)[0]->water_level;
        
            if ($nextHourData[0]->precipitation == $previousRI) {
                updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else if ($nextHourData[0]->precipitation > $previousRI) {
               $previousWL += $nextHourData[0]->water_level;
                    if ($previousWL < 0) {
                        $previousWL = 0;
                    }
                updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else if ($nextHourData[0]->precipitation < $previousRI) {
                $previousWL -= $nextHourData[0]->water_level;
                    if ($previousWL < 0) {
                        $previousWL = 0;
                    }
                updateActualWL($previousWL, $nextHourData[0]->time, $i);
            } else {
                echo "No action was performed.";
            }
           
            for($k = 1; $k < count($nextHourData); $k++) {
                if ($nextHourData[$k]->precipitation == $nextHourData[$k-1]->precipitation) {
                    updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else if ($nextHourData[$k]->precipitation > $nextHourData[$k-1]->precipitation) {
                    $previousWL += $nextHourData[$k]->water_level;
                        if ($previousWL < 0) {
                            $previousWL = 0;
                        }
                    updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else if ($nextHourData[$k]->precipitation < $nextHourData[$k-1]->precipitation) {
                    $previousWL -= $nextHourData[$k]->water_level;
                        if ($previousWL < 0) {
                            $previousWL = 0;
                        }
                    updateActualWL($previousWL, $nextHourData[$k]->time, $i);
                } else {
                    echo "No action was performed!";
                }
            }
     
    }
}


/**
 * Fetching the previous forecast before upcoming forecasts
 * @param int $drainid - The drain to get the forecast
 * @return array
 */
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

/**
 * Fetching all 4 upcoming forecasts based on drain id
 * @param int $drainid - The drain id to get the forecast from
 * @return array
 */
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

/**
 * Updating the actual_water_level field with the calculated water level for each drain
 * @param double $waterLevel - The calculated water level
 * @param string $ftime - The forecast time of the specific record
 * @param int $drainid - The id of the drain
 * @return void
 */
function updateActualWL($waterLevel, $ftime, $drainid) {
    global $conn;
    $sql = "UPDATE rainfall SET actual_water_level = '$waterLevel' WHERE forecast_time = '$ftime' AND status = 'active' AND drain_id = '$drainid'";
    mysqli_query($conn, $sql);
}

/**
 * Decreasing the format time by 1 hour and formatting
 * @param string $time - The time to format
 * @return string
 */
function formatForecastTime($time) {
    $res = date('H:i', strtotime($time . '-1 hour'));
    return $res;
}

?>