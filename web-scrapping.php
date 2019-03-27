<?php
    include ('dbconnect.php');
    include ('readarff.php');

    $drain1_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain1.txt');
    $drain2_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain2.txt');
    $drain3_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain3.txt');

    $drain1_array = getPredictedValues(4, $drain1_prediction);
    $drain2_array = getPredictedValues(4, $drain2_prediction);
    $drain3_array = getPredictedValues(4, $drain3_prediction);

    $drains_predictions = array($drain1_array, $drain2_array, $drain3_array);

    /**
     * Fetching data from URL  of the website / Performing scrapping
     * Creating an array of 4 objects (4 upcoming forecasts)
     * Accounts for if one URL Endpoint is down, use the second one
     * Note: Priority-wise (Forecast.co.uk then yr.no)
     * @return array
     */
    function fetchDataFromXPath() {
        $forecastuk = file_get_contents('https://www.forecast.co.uk/mauritius/port-louis.html?v=per_hour');
        $yr = file_get_contents('https://www.yr.no/place/Mauritius/Port_Louis/Cassis/hour_by_hour.html');
    
        $forecast_doc = new DOMDocument();
        
        libxml_use_internal_errors(TRUE); //disable libxml errors
    
        $forecast_array = array();
    
        if(!empty($forecastuk)) {
            $forecast_doc->loadHTML($forecastuk);
            libxml_clear_errors();
        
            $forecast_xpath = new DOMXPath($forecast_doc);
        
            $time_row = $forecast_xpath->query('//td[@class="hour"]');
            $precipitation_row = $forecast_xpath->query('//td[@class="precipitation"]');
        
            $time = getItems($time_row, 1, 4);
            $precipitation = getItems($precipitation_row, 1, 4);
            $forecast_array = createForecastObject($time, $precipitation, 4);//store 4 new data of type object in an array
            return $forecast_array;
        
        } else {
            $forecast_doc->loadHTML($yr);
            libxml_clear_errors();
        
            $forecast_xpath = new DOMXPath($forecast_doc);
        
            $time_row = $forecast_xpath->query('//td[@scope="row"]//strong');
            $precipitation_row = $forecast_xpath->query('//td[@class="precipitation"]');
        
            $time = getItems($time_row, 0, 4);
            $precipitation = getItems($precipitation_row, 0, 4);
            $forecast_array = createForecastObject($time, $precipitation, 4);
            return $forecast_array;
        }
    }


    // START - The main logic
    $forecast_array = fetchDataFromXPath(); // Fetching data from API Endpoint (Upcoming 4 forecast)
    $allData_array = selectAllData(); //select all data from db (active or inactive)
    $predictions = getAllPredictionValues(4, $drains_predictions); //arff data

    //compare data exist in db to new forecast data next hour 
    // param1 - All the data from db
    // param2 - The 4 new forecast objects
    $difference_array = array_udiff($allData_array, $forecast_array,
        function ($obj_a, $obj_b) {
            return strcmp($obj_a->time, $obj_b->time);
        }
    );
 
    print_r($difference_array);
    //array_udiff function always return only that is match
    // All data from db - ..., 5:00, 6:00, 7:00
    // 4 upcoming forecasts - 5:00, 6:00, 7:00, 8:00
    // Difference - 5:00, 6:00, 7:00

    // Update the time and rainfall in case there is a change on fcuk per hour
    foreach($difference_array as $object) {
       updateForecast($object->time, $object->date);
    }


    // If data already exists in db,
    // Then update intensity and water level
    // Else, if data does not exist
    // Then, insert the new data in db
   $count = 0;
   for($i = 0; $i < 4; $i++) { //forecast array
       if (isDataExists($forecast_array[$i]->time, $forecast_array[$i]->date)) {
           echo "Data already exists!";
           print_r($forecast_array[$i]);
           echo "<br/>";
           updateIntensity($forecast_array[$i]->precipitation, $forecast_array[$i]->time, $forecast_array[$i]->date);
          
           for($j = 1; $j < 4; $j++) {
               updateWaterLevel($predictions[$count], $forecast_array[$i]->time, $forecast_array[$i]->date, $j);
               $count++;
           }
       } else {
           for($j = 0; $j < 3; $j++) {
               insertSQL($forecast_array[$i], $j + 1);
               $count++;
           }
       }
   }
   // END - The main logic

    /**
     * Getting items from XPath
     * Convert NodeListObject to Array and using Splice to only receive a specific quantity of values from the array
     * @param string $path
     * @param int $offset
     * @param int $length
     * @return array
     */
    function getItems($path, $offset, $length) {
        $array = iterator_to_array($path);//get data from fcuk
        $items = array_splice($array, $offset, $length);//take data 1-4
        return $items;
    }

    /**
     * Creating Forecast objects with time and precipitation
     * Storing the created objects in an Array
     * @param array $time
     * @param array $precipitation
     * @param int $length
     * @return array
     */
    function createForecastObject($time, $precipitation, $length) {
        $forecast_array = array();
        for($i = 0; $i < $length; $i++) {
            $object = (object) [
                'time' => $time[$i]->nodeValue,
                'precipitation' => trim($precipitation[$i]->nodeValue, " mm"),
                'date' => getCurrentDate()
            ];
            array_push($forecast_array, $object);
        }
        return $forecast_array;
    }

    /**
     * Insert object (Time, Precipitation) into MySQL Table
     * @param object $object
     * @param int $drain_id
     * @return void
     */
    function insertSQL($object, $drain_id) {
        global $conn;
        $current_date = getCurrentDate();
        $sql_drain1 = "INSERT INTO rainfall (forecast_time,rainfall_intensity, status, date, water_level , drain_id) VALUES ('$object->time', '$object->precipitation', 'active', '$current_date', 0 ,$drain_id)";
        mysqli_query($conn, $sql_drain1);
        echo "Data added!";
    }

     /**
     * Update all the status that are active to inactive
     * @return void
     */
    function updateStatus() {
        global $conn;
        $update = "UPDATE rainfall SET status = 'inactive'";
        mysqli_query($conn, $update);
    }

    /**
     * Get current date based on Timezone (GMT +4)
     * @return string
     */
    function getCurrentDate() {
        $now = new DateTime('now', new DateTimeZone('Indian/Mauritius'));
        return $now->format('d/m/Y H:i');
    }

    /**
     * Check if object to be inserted already exists in database
     * To prevent duplicate data
     * @param string $ftime
     * @param string $date
     * @return boolean
     */
    function isDataExists($ftime, $date) {
        global $conn;

        // date is originally 25/03/2019 16:34
        // But I only need the date and not the time
        // To extract only date, I used str_split
        $newdate = str_split($date, 10)[0];
        $query = "SELECT forecast_time, date FROM rainfall WHERE forecast_time = '$ftime' AND date LIKE '%$newdate%'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update forecast table setting status to inactive based on forecast time and date
     * @param int $ftime
     * @param string $date
     * @return void
     */
    function updateForecast($ftime, $date) {
        global $conn;
        $sql = "UPDATE rainfall SET status = 'inactive' WHERE forecast_time = '$ftime' AND date = '$date'";
        mysqli_query($conn, $sql);
        
    }

    /**
     * Updating records and insertion of water level
     * @param int $water_level
     * @param string $ftime
     * @param string $date
     * @param int $drainid
     * @return void
     */
    function updateWaterLevel($water_level, $ftime, $date, $drainid) {
        global $conn;
        $newdate = str_split($date, 10)[0];
        $update = "UPDATE rainfall SET water_level = '$water_level' WHERE forecast_time = '$ftime' AND date LIKE '%$newdate%' AND drain_id = '$drainid'";
        mysqli_query($conn, $update);
    }

    /**
     * Selecting all data from rainfall and creating array of objects with result
     * @return array
     */
    function selectAllData() {
        global $conn;
        $array = array();
        $query = "SELECT * FROM rainfall";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $object = (object) [
                    'time' => $row['forecast_time'],
                    'precipitation' => $row['rainfall_intensity'],
                    'date' => $row['date']
                ];
                array_push($array, $object);
            }
        } else {
            echo 'No data';
        }
        return $array;
    }

    /**
     * Updating all the forecast intensity with latest values from api endpoint
     * @param int $intensity
     * @param string $ftime
     * @param string $date
     * @return void
     */
    function updateIntensity($intensity, $ftime, $date) {
        global $conn;
        $newdate = str_split($date, 10)[0];
        $update = "UPDATE rainfall SET rainfall_intensity ='$intensity' WHERE forecast_time = '$ftime' AND date LIKE '%$newdate%'";
        mysqli_query($conn, $update);
    }

?>