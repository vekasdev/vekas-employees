<?php

namespace App\Model;

use DateTime;
use DateTimeImmutable;

class DaysScanner {

    /**
     * scan month and return an array of DayTime object for every day it has
     * @param int $year
     * @param int $month
     * @return array<string, DateTimeImmutable>
     */
    public static function scan(int $year, int $month) {
        $date = new DateTimeImmutable("$year-$month-2");
        $numberOfDays = $date->format("t");
        $data = [];
        for($i=1; $i <= $numberOfDays; $i++) {
            $currentDay = new DateTimeImmutable("$year-$month-$i");
            // $currentDayName = $currentDay->format("l");
            $data[$i] = $currentDay;
        }
        return $data;
    }

}