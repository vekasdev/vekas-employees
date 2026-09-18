<?php

namespace App\Service;

class ApplicationInitializer {
    function __construct(
        private MonthService $monthService,
        private WorkdayService $workdayService
    )
    {}

    function initialize() {
        $this->workdayService->updateMonthDayOff();
        // here add things that need to be initilized
    }
}