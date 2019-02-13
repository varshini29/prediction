<?php

include ('dbconnect.php');

use Cocur\Arff\Document;
use Cocur\Arff\Column\NumericColumn;
use Cocur\Arff\Column\NominalColumn;
use Cocur\Arff\Writer;

require __DIR__ . '/vendor/autoload.php';

/**
 * Creating objects of time & precipitation from MySQL Statement
 * Adding of the objects to an array
 * @return array
 */
function getForecast() {
    $times = array();
    global $conn;
    $sql = "SELECT * FROM rainfall WHERE status = 'active' AND drain_id = 2";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows ($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $object = (object) [
                'time' => $row['forecast_time'],
                'precipitation' => $row['rainfall_intensity']
            ];
            array_push($times, $object);
        }
    }
    return $times;
}

function calculatePeakDischarge($rainfall) {
    // Q = 0.278 * 0.7 * i * 0.122
    // ans :Q m3/s
    //y = x * 35.315 cfs
    $peak = $rainfall * 0.278 * 0.7 * 0.3;
    $result = $peak * 35.315;
    return round($result, 3);
    //Q=AV
    //A=b*y
}

$document = new Document("drain2");

$document->addColumn(new NumericColumn('forecasted_rainfall_intensiy'));
$document->addColumn(new NumericColumn('depth_of_drain'));
$document->addColumn(new NumericColumn('peak_discharge'));
$document->addColumn(new NumericColumn('drainage_capacity'));
$document->addColumn(new NumericColumn('water_level'));

$datasets = getForecast();
print_r($datasets);
foreach($datasets as $value) {
   // print_r($value->precipitation);
    $peak_discharge = calculatePeakDischarge($value->precipitation);
    $document->addData(['forecasted_rainfall_intensiy' => $value->precipitation, 'depth_of_drain' => 7.22, 'peak_discharge' => $peak_discharge, 'drainage_capacity' => 70.79]);
    //$document->addData(['depth_of_drain' => '3.0']);
    //$document->addData(['peak_discharge' => '1.0']);
}

// $document->addData(['sepallength' => 5.1, 'sepallength' => 3.5, 'sepallength' => 1.4, 'sepallength' => 0.2]);
// $document->addData(['sepallength' => 5.1, 'sepallength' => 3.5, 'sepallength' => 1.4, 'sepallength' => 0.2]);
// $document->addData(['sepallength' => 5.1, 'sepallength' => 3.5, 'sepallength' => 1.4, 'sepallength' => 0.2]);
// $document->addData(['sepallength' => 5.1, 'sepallength' => 3.5, 'sepallength' => 1.4, 'sepallength' => 0.2]);


$writer = new Writer();

$writer->render($document);
$writer->write($document, 'C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\forecast_d2.arff');

?>