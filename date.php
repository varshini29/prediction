<?php

    /**
     * Get current date using Timezone GMT +4
     * @return string
     */
    function getCurrentDate() {
        $now = new DateTime('now', new DateTimeZone('Indian/Mauritius'));
        return $now->format('d/m/Y H:i');
    }

    /**
     * Get the date based on an index. E.g (If looping through 3, will display 3 data for 3 days)
     * @param integer $index
     * @return string
     */
    function getLastWeek($index) {
        return date("m/d/Y", strtotime($index."days ago")).'<br/>';
    }

    /**
     * Get starting day of last week
     * @return string
     */
    function getStartingDayLastWeek() {
        $date = new DateTime('7 days ago', new DateTimeZone('Indian/Mauritius'));
        return $date->format('d/m/Y H:i');
    }

    /**
     * Get last day of last week = the previous day = 1 day ago
     * @return string
     */
    function getEndingDayLastWeek() {
        $date = new DateTime('1 day ago', new DateTimeZone('Indian/Mauritius'));
        return $date->format('d/m/Y H:i');
    }

    /**
     * Get previous days based on the given number of days
     * @param string $days
     * @return string
     */
    function getPreviousDays($days) {
        $date = new DateTime($days, new DateTimeZone('Indian/Mauritius'));
        return $date->format('d/m/Y H:i');
    }

    function getDataFromDate($date) {
        global $conn;
        $query = "SELECT * FROM rainfall WHERE date = '$date'";
        mysqli_query($conn, $query);
    }

?>