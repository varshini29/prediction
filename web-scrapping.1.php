<?php
    include ('dbconnect.php');
    include ('readarff.php');

    $html = file_get_contents('https://www.forecast.co.uk/mauritius/port-louis.html?v=per_hour'); //get the html returned from the following url
    
    $drain1_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain1.txt');
    $drain2_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain2.txt');
    $drain3_prediction = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain3.txt');

    $drain1_array = getPredictedValues(4, $drain1_prediction);
    $drain2_array = getPredictedValues(4, $drain2_prediction);
    $drain3_array = getPredictedValues(4, $drain3_prediction);

   // $drains_predictions = array($drain1_array,$drain3_array, $drain2_array);

    $forecast_doc = new DOMDocument();

    libxml_use_internal_errors(TRUE); //disable libxml errors

    if(!empty($html)) {
        $forecast_doc->loadHTML($html);
        libxml_clear_errors(); //remove errors for yucky html
  
        $forecast_xpath = new DOMXPath($forecast_doc);

        $time_row = $forecast_xpath->query('//td[@class="hour"]');
        $precipitation_row = $forecast_xpath->query('//td[@class="precipitation"]');

        $time = getItems($time_row);
        $precipitation = getItems($precipitation_row); 

        
        /*
            $forecast_array retrieve rainfall intensity from url
        */
        $forecast_array = createForecastObject($time, $precipitation, 4); 

        //$allData_array = selectAllData();  //retrieve all existing data from database.

        print_r($forecast_array);
        echo'</br></br>';

       // print_r($allData_array);

       updateForecast(); //update all previous value in db base on current time and current date where status is active
        
    

        // print_r($drain1_array[0]);
        // echo'</br>';
        // print_r($drain2_array[0]);
        // echo'</br>';
        // print_r($drain3_array[0]);
        // echo'</br>';

        //drain 1
        UpdateDrain(1,$drain1_array[0],$forecast_array);

        //drain 2
       UpdateDrain(2,$drain2_array[0],$forecast_array);

        //drain 3
       UpdateDrain(3,$drain3_array[0],$forecast_array);

    }

    /**
     * Getting items from XPath
     * Convert NodeListObject to Array and using Splice to only receive a specific quantity of values from the array
     * @param string $path
     * @return array
     */
    function getItems($path) {
        $array = iterator_to_array($path);
        $items = array_splice($array, 1, 4);
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
     * @return void
     */
  

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
   

    /**
     * Update forecast table setting status to inactive based on forecast time and date
     * @param int $ftime
     * @param string $date
     * @return void
     */

    function updateForecast() {
        global $conn;
    //    // $sql = "UPDATE rainfall SET status = 'inactive'
    //     WHERE forecast_time = '$ftime' AND date = '$date'";

      $sql=" UPDATE rainfall 
                SET status = 'inactive' 
             WHERE TIME_FORMAT(CAST(forecast_time AS TIME),'%H:%i') <=TIME_FORMAT(NOW(),'%H:%i')
             AND DATE_FORMAT(STR_TO_DATE(date,'%d/%m/%Y %H:%i'), '%d/%m/%Y') <= DATE_FORMAT(NOW(),'%d/%m/%Y')
             AND status = 'active'";

        mysqli_query($conn, $sql);
        
    }

    /**
     * Updating records and insertion of water level
     * @param int $water_level
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
     * @return void
     */
  

    function UpdateDrain($drain,$array,$forecast_array)
    {
        $index=0;
        foreach($array as $value)
        {
                echo'Time:'.$forecast_array[$index]->time.' Date:'. $forecast_array[$index]->date.'</br>';

                if (isDataExists($forecast_array[$index]->time)) 
                {
                    echo "Data already exists!";
                    updateWaterLevel($value, $forecast_array[$index]->time, $forecast_array[$index]->date,$drain);
                    updateIntensity($forecast_array[$index]->precipitation,$forecast_array[$index]->time);
                    for($j = 1; $j < 3; $j++) 
                    {
                        print_r($forecast_array[$j]);
                        echo "<br/>";
                    }
                } 
                else 
                {
                    for($j = 0; $j < 3; $j++) 
                    {
                        // insertSQL($forecast_array[$i], $j + 1, $predictions[$count]);
                        insertSQL($forecast_array[$index], $j + 1);
                    }
                }
           $index++;
        }
    }

/*The following are functions used in data manipulations */

    function isDataExists($ftime) {
        global $conn;
        //$newdate = str_split($date, 10)[0];
        //$query = "SELECT forecast_time, date FROM rainfall WHERE forecast_time = '$ftime' AND date LIKE '%$newdate%'";

        $query ="SELECT forecast_time, date FROM rainfall 
                    WHERE forecast_time = '$ftime' 
                    AND DATE_FORMAT(STR_TO_DATE(date,'%d/%m/%Y %H:%i'), '%d/%m/%Y') <= DATE_FORMAT(NOW(),'%d/%m/%Y') AND status = 'active'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function insertSQL($object, $drain_id) {
        global $conn;
        $current_date = getCurrentDate();
        $sql_drain1 = "INSERT INTO rainfall (forecast_time,rainfall_intensity, status, date, water_level , drain_id) VALUES ('$object->time', '$object->precipitation', 'active', '$current_date', 0 ,$drain_id)";
        mysqli_query($conn, $sql_drain1);
        echo "Data added!";
    }

    function updateIntensity($intensity,$ftime) {
        global $conn;
        
        $update = "UPDATE rainfall SET rainfall_intensity ='$intensity'
        WHERE forecast_time = '$ftime'  
        AND DATE_FORMAT(STR_TO_DATE(date,'%d/%m/%Y %H:%i'), '%d/%m/%Y') <= DATE_FORMAT(NOW(),'%d/%m/%Y') 
        AND status = 'active'";

        mysqli_query($conn, $update);
    }

?>