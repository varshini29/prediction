<?php
include('dbconnect.php');
$html = file_get_contents('https://www.forecast.co.uk/mauritius/port-louis.html?v=per_hour'); //get the html returned from the following url

$forecast_doc = new DOMDocument();

libxml_use_internal_errors(TRUE); //disable libxml errors

if(!empty($html)){ //if any html is actually returned

  $forecast_doc->loadHTML($html);
  libxml_clear_errors(); //remove errors for yucky html
  
  $forecast_xpath = new DOMXPath($forecast_doc);

  $forecasthour_row = $forecast_xpath->query('//td[@class="hour"]');
  $forecast_row = $forecast_xpath->query('//td[@class="precipitation"]');

  // Time loops
  
 // if($forecasthour_row->length > 0){
    /*$count_rain = 0;
    foreach($forecasthour_row as $rowhour){
      if($rowhour->nodeValue == 0) {
        continue;
      }
        $time=$rowhour->nodeValue;
        $count_rain++;
        echo $time;
        echo "<br>";

        if ($count_rain == 4) {
          break;
        }
      }*/
      
      // Precipation loops
      $count_precipitation = 0;
      foreach($forecast_row as $row){
        if ($row->nodeValue == 0) {
          continue;
        }

      $rain= $row->nodeValue;
      $count_precipitation++;
      $rain_trim= trim($rain," mm");
      echo $rain_trim;
      echo "<br>";

      if ($count_precipitation == 5) {
        break;
      }

      $sql = "INSERT INTO rainfall (rainfall_intensity) VALUES ('$rain_trim')";
       if (mysqli_query($conn, $sql)) {
         echo "New record created successfully";
     } else {
         echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     }
      //$myfile = fopen("rain.arff", "a") or die("Unable to open file!");
      //$format = $rain_trim.",";
      //fwrite($myfile, $format);
      //echo $rain_trim;
      //echo "<br>";
   }
  }
//}
mysqli_close($conn);

?>