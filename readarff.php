<?php

$file = file_get_contents('C:\Users\varsh\Documents\Applied-Computing\Dissertation\WEKA\ANN\drain1.txt');


  /**
   * Creating array of repeated values for Regular Expressions
   * @param int $length, number of predicted values to extract
   * @return array
   */
  function regEx($length) {
    $array = array();
    for ($i = 1; $i < $length * 2 + 1; $i++) {
      if ($i % 2 == 0) {
        array_push($array, ($array[$i] = '([+-]?\\d*\\.\\d+)(?![-+0-9\\.])'));
      } else {
        array_push($array, ($array[$i] = '.*?'));
      }
    }
    unset($array[$length * 2 + 1]);
    return $array;
  }

  /**
   * Getting the predicted values and storing in an array
   * @param int $length
   * @param string $file
   * @return array
   */
  function getPredictedValues($length, $file) {
    $regex_array = regEx($length);
    for($i = 1; $i < count($regex_array) + 1; $i++) {
      $c = preg_match_all("/".$regex_array[$i]."/is", $file, $matches);
    }
    return $matches;
  }


  /**
   * Getting all prediction values based on their indexes in order of their indexes
   * @param int $length
   * @param array $drains
   * @return array
   */
  function getAllPredictionValues($length, $drains) {
    $result = array();
    for($i = 0; $i < $length; $i++) {
      for($j = 0; $j < $length - 1; $j++) {
        array_push($result, $drains[$j][0][$i]);
      }
    }
    return $result;
  }


?>

