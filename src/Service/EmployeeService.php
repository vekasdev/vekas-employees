<?php


namespace App\Service;

use App\Entity\Attendence;
use App\Entity\Employee;
use App\Factory\MonthDataFactory;
use App\Model\DayData;
use App\Repository\MonthRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use InvalidArgumentException;

class EmployeeService {

    function __construct(
        private MonthRepository $monthRepository,
        private MonthDataFactory $monthDataFactory,
    )
    {
        
    }

    function initCurrentDay() {
        
    }


    /**
     * checks if employee have a working hours for the specific attenence
     * @param Employee $employee
     * @param Attendence $attendence
     * @throws InvalidArgumentException
     * @return bool
     */
    function isEligitimate(Employee $employee,Attendence $attendence) {
        if ( $employee->getAttendences()->exists(
            fn( Attendence $a)=>$attendence->getId() === $a->getId()
        ) ) throw new InvalidArgumentException("attendence must be related to employee");
        
        $workingHours = $employee->getWorkingHours();
        return $attendence->getWorkingHours() >= $workingHours;
    }
 
    function getTotalWorkingHours(Employee $employee, $period = "all_time",DateTime 
    | NULL $month = null) {

        
        $hoursCount = 0;
        foreach($employee->getAttendences() as $attendence) {
            $hoursCount += $attendence->getWorkingHours();
        }
        return $hoursCount;

    }

    function getAbsentDays(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): int {
        $absentDays = 0;
        $fromDate = $from->format('Y-m-d');
        $toDate = $to->format('Y-m-d');
        $month = DateTimeImmutable::createFromInterface($from)->modify('first day of this month');

        while ($month->format('Y-m-d') <= $toDate) {
            $monthData = $this->monthDataFactory->createFromEmployee(
                $employee,
                (int) $month->format('Y'),
                (int) $month->format('n'),
            );

            /** @var DayData $dayData */
            foreach ($monthData->getDays() as $dayData) {
                $dayDate = $dayData->getDate();
                $attendance = $dayData->getAttendence();
                $attendanceDate = $dayDate->format('Y-m-d');

                if (
                    $attendanceDate >= $fromDate
                    && $attendanceDate <= $toDate
                    && $dayData->isPassed()
                    && !$dayData->isOff()
                    && ($attendance === null || $attendance->getCheckIn() === null)
                ) {
                    $absentDays++;
                }
            }

            $month = $month->modify('+1 month');
        }

        return $absentDays;
    }

    function getCommitmentPercentage(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): float {
        $fromDate = $from->format('Y-m-d');
        $toDate = $to->format('Y-m-d');
        $today = (new DateTimeImmutable('today'))->format('Y-m-d');
        $registeredMonths = [];
        foreach ($this->monthRepository->findAllOrdered() as $month) {
            $date = $month->getDate();
            if ($date !== null) {
                $registeredMonths[$date->format('Y-m')] = true;
            }
        }

        $presentDates = [];

        foreach ($employee->getAttendences() as $attendence) {
            $date = $attendence->getDate();
            if ($date === null) {
                continue;
            }

            $attendanceDate = $date->format('Y-m-d');
            if (
                isset($registeredMonths[$date->format('Y-m')])
                && $attendanceDate >= $fromDate
                && $attendanceDate <= $toDate
                && $attendanceDate < $today
                && $attendence->getCheckIn() !== null
                && !$employee->isDayOff($date)
            ) {
                $presentDates[$attendanceDate] = true;
            }
        }

        $scheduledWorkdays = 0;
        $day = DateTimeImmutable::createFromInterface($from)->setTime(0, 0);
        while ($day->format('Y-m-d') <= $toDate && $day->format('Y-m-d') < $today) {
            if (
                isset($registeredMonths[$day->format('Y-m')])
                && !$employee->isDayOff($day)
            ) {
                $scheduledWorkdays++;
            }

            $day = $day->modify('+1 day');
        }

        if ($scheduledWorkdays === 0) {
            return 0.0;
        }

        return count($presentDates) / $scheduledWorkdays * 100;
    }

    function getLateDays(Employee $employee, DateTimeInterface $from, DateTimeInterface $to): int {
        $lateDays = 0;
        $fromDate = $from->format('Y-m-d');
        $toDate = $to->format('Y-m-d');

        foreach ($employee->getAttendences() as $attendence) {
            $date = $attendence->getDate();
            if ($date === null || $attendence->getCheckIn() === null) {
                continue;
            }

            $attendanceDate = $date->format('Y-m-d');
            if (
                $attendanceDate >= $fromDate
                && $attendanceDate <= $toDate
                && !$employee->isDayOff($date)
                && AttendenceService::isAttendenceLate($attendence)
            ) {
                $lateDays++;
            }
        }

        return $lateDays;
    }


}