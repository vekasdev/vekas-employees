<?php

namespace App\Service;

use App\Entity\Month;
use App\Entity\WeeklyDayOff;
use App\Model\DaysScanner;
use App\Repository\DayOffRepository;
use App\Repository\MonthRepository;
use App\Repository\WeeklyDayOffRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class WorkdayService {
    function __construct(
        private WeeklyDayOffRepository $weeklyDayOffRepository,
        private DayOffRepository $dayOffRepository,
        private MonthRepository $monthRepository,
        private EntityManagerInterface $em,
        private DayOffService $dayOffService,
        private MonthService $monthService
    )
    {}

    /**
     * iterate over weeklydayoff and create dayOffs records
     * @return void
     */
    function updateMonthDayOff() {

        $monthEntity = $this->monthService->initMonth();

        if ($monthEntity->isDayOffPopulated()) {
            return;
        }

        $now = new DateTime();
        $year = (int) $now->format("Y");
        $month = (int) $now->format("m");
        $weeklyDaysOff = $this->weeklyDayOffRepository->findAll();
        $days = DaysScanner::scan($year,$month);

        // in order to track if the month day offs are already populated or not
        $dayOffsRegisteredCount = 0;

        foreach($days as $day) {
            /** @var WeeklyDayOff */
            foreach($weeklyDaysOff as $weeklyDayOff) {
                if ($weeklyDayOff->getDay()->value ===  strtolower($day->format("l"))) {
                    // check if day off are exist for this month
                    $dayOff = $this->dayOffRepository->findByDate($day);
                    if (count($dayOff)) {
                        continue;
                    }
                    $dayOff = $this->dayOffService->create($day);

                    // increase the number to ensure that the month day offs populated if it more than 0
                    $dayOffsRegisteredCount++;
                }              
            }
        }
        
        if ($dayOffsRegisteredCount) {
            $monthEntity->setIsDayOffPopulated(true);
            $this->em->persist($monthEntity);
            $this->em->flush();
        }

    }

}