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
        private DayOffService $dayOffService
    )
    {}

    /**
     * create a day offs according to the WeeklyDayOff entity
     * @return bool|mixed|Month
     */
    function initMonth() : Month {
        $now = new DateTimeImmutable();
        $month = $this->monthRepository->findByDate($now);
        if (count($month)) {
            return end($month);
        }
        $month = $this->monthRepository->create(new DateTimeImmutable());
        $this->em->persist($month);
        $this->em->flush();
        return $month;
    }

    /**
     * iterate over weeklydayoff and create dayOffs records
     * @return void
     */
    function updateMonthDayOff() {

        $monthEntity = $this->initMonth();
        if ($monthEntity->isDayOffPopulated()) {
            return;
        }
        $now = new DateTime();
        $year = (int) $now->format("Y");
        $month = (int) $now->format("m");
        $weeklyDaysOff = $this->weeklyDayOffRepository->findAll();
        $days = DaysScanner::scan($year,$month);

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
                }              
            }
        }
        
        $monthEntity->setIsDayOffPopulated(true);
        $this->em->persist($monthEntity);
        $this->em->flush();
    }

}