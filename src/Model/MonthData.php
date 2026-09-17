<?php

namespace App\Model;

use DateTime;
use DateTimeImmutable;

class MonthData {

    /**
     * @var array<DayData>
     */
    private array $days = [];
    
    function __construct(
        private int $year,
        private int $month
    )
    {
        $this->init();
    }

    function getDays() {
        return $this->days;
    }

    function init() {
        $scannedDays = DaysScanner::scan($this->year,$this->month);
        $days = [];
        for($i = 1 ; $i <= count($scannedDays) ; $i++) {
            $dateTime = new DateTimeImmutable("$this->year-$this->month-$i");
            $days[] = new DayData($scannedDays[$i],$dateTime);
        }
        $this->days = $days;
    }

  
    
}