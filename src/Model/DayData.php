<?php

namespace App\Model;

use App\Entity\Attendence;
use DateTimeImmutable;

class DayData {

    function __construct(
        private $name,
        private DateTimeImmutable $date,
        private bool $off = false,
        private ?Attendence $attendence = null
        
    )
    {}

    function isPassed() {
        $now = new DateTimeImmutable();
        $now = $now->setTime(0,0,0);

        return $this->date->getTimestamp() < $now->getTimestamp();
    }

    function isOff() {
        return $this->off;
    }

    function getDate() {
        return $this->date;
    }

    function setOff(bool $off) {
        $this->off = $off;
    }

    /**
     * Get the value of attendence
     */ 
    public function getAttendence()
    {
            return $this->attendence;
    }

    /**
     * Set the value of attendence
     *
     * @return  self
     */ 
    public function setAttendence($attendence)
    {
            $this->attendence = $attendence;

            return $this;
    }

    function isLate() {
        
    }
}