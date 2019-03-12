<?php

function fetchDataFromXPath() {
    $forecastuk = file_get_contents('https://www.forecast.co.uk/mauritius/port-louis.html?v=per_hour');
    $yr = file_get_contents('https://www.yr.no/place/Mauritius/Port_Louis/Cassis/hour_by_hour.html');

    $forecast_doc = new DOMDocument();
    
    libxml_use_internal_errors(TRUE); //disable libxml errors

    $forecast_array = array();

    if(!empty($forecastuk)) {
        $forecast_doc->loadHTML($forecastuk);
        libxml_clear_errors(); //remove errors for yucky html
    
        $forecast_xpath = new DOMXPath($forecast_doc);
    
        $time_row = $forecast_xpath->query('//td[@class="hour"]');
        $precipitation_row = $forecast_xpath->query('//td[@class="precipitation"]');
    
        $time = getItems($time_row, 1, 4);
        $precipitation = getItems($precipitation_row, 1, 4);
        $forecast_array = createForecastObject($time, $precipitation, 4);
        print_r('From ForecastUK');
        return $forecast_array;
    
    } else {
        $forecast_doc->loadHTML($yr);
        libxml_clear_errors(); //remove errors for yucky html
    
        $forecast_xpath = new DOMXPath($forecast_doc);
    
        $time_row = $forecast_xpath->query('//td[@scope="row"]//strong');
        $precipitation_row = $forecast_xpath->query('//td[@class="precipitation"]');
    
        $time = getItems($time_row, 0, 4);
        $precipitation = getItems($precipitation_row, 0, 4);
        $forecast_array = createForecastObject($time, $precipitation, 4);
        print_r('From YR');
        return $forecast_array;
    }
}

$forecast_array = fetchDataFromXPath();
print_r('Testing');
print_r($forecast_array);


function getItems($path, $offset, $length) {
    $array = iterator_to_array($path);
    $items = array_splice($array, $offset, $length);
    return $items;
}

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

function getCurrentDate() {
    $now = new DateTime('now', new DateTimeZone('Indian/Mauritius'));
    return $now->format('d/m/Y H:i');
}

?>