<?php

namespace App\Tests\Service;

use App\Entity\Attendence;
use App\Entity\DayOff;
use App\Entity\Employee;
use App\Entity\Month;
use App\Factory\MonthDataFactory;
use App\Model\MonthData;
use App\Repository\MonthRepository;
use App\Service\EmployeeService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class EmployeeServiceTest extends TestCase
{
    function test_get_total_absent_days_excludes_attended_and_day_off_dates(): void
    {
        $employee = new Employee();
        $service = $this->createEmployeeService();

        // absent
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-05'))
        );
        // on time
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-06'))
                ->setCheckIn(new DateTimeImmutable('2026-01-06 09:00:00'))
        );
        // day off
        $employee->addDayOff(
            (new DayOff())->setDate(new DateTimeImmutable('2026-01-07'))
        );
        // absent
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-07'))
        );

        self::assertSame(
            1,
            $service->getAbsentDays(
                $employee,
                new DateTimeImmutable('2026-01-05'),
                new DateTimeImmutable('2026-01-07'),
            ),
        );
    }

    function test_get_total_absent_days_counts_range_endpoints_and_excludes_dates_outside_range(): void
    {
        $employee = new Employee();
        $service = $this->createEmployeeService();

        foreach (['2026-01-04', '2026-01-05', '2026-01-07', '2026-01-08'] as $date) {
            $employee->addAttendence(
                (new Attendence())->setDate(new DateTimeImmutable($date))
            );
        }

        self::assertSame(
            3,
            $service->getAbsentDays(
                $employee,
                new DateTimeImmutable('2026-01-05 23:59:59'),
                new DateTimeImmutable('2026-01-07 00:00:00'),
            ),
        );
    }

    function test_get_total_absent_days_counts_days_without_an_attendance_record(): void
    {
        $employee = new Employee();
        $service = $this->createEmployeeService();
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-05'))
                ->setCheckIn(new DateTimeImmutable('2026-01-05 09:00:00'))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-07'))
                ->setCheckIn(new DateTimeImmutable('2026-01-07 09:00:00'))
        );

        self::assertSame(
            1,
            $service->getAbsentDays(
                $employee,
                new DateTimeImmutable('2026-01-05'),
                new DateTimeImmutable('2026-01-07'),
            ),
        );
    }

    function test_get_late_days_counts_late_check_ins_within_the_range(): void
    {
        $employee = (new Employee())
            ->setWorkStartTime(new DateTimeImmutable('2000-01-01 09:00:00'));
        $service = $this->createEmployeeService();

        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-05'))
                ->setCheckIn(new DateTimeImmutable('2026-01-05 09:01:00'))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-06'))
                ->setCheckIn(new DateTimeImmutable('2026-01-06 09:00:00'))
        );
        $employee->addDayOff(
            (new DayOff())->setDate(new DateTimeImmutable('2026-01-07'))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-07'))
                ->setCheckIn(new DateTimeImmutable('2026-01-07 09:30:00'))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate(new DateTimeImmutable('2026-01-04'))
                ->setCheckIn(new DateTimeImmutable('2026-01-04 09:30:00'))
        );
        $employee->addAttendence(
            (new Attendence())->setDate(new DateTimeImmutable('2026-01-08'))
        );

        self::assertSame(
            1,
            $service->getLateDays(
                $employee,
                new DateTimeImmutable('2026-01-05 23:59:59'),
                new DateTimeImmutable('2026-01-07 00:00:00'),
            ),
        );
    }

    function test_get_commitment_percentage_counts_present_days_over_scheduled_workdays(): void
    {
        $employee = new Employee();
        $monthStart = (new DateTimeImmutable('first day of previous month'))->setTime(0, 0);
        $service = $this->createEmployeeService([$this->registeredMonth($monthStart)]);

        $employee->addAttendence(
            (new Attendence())
                ->setDate($monthStart->modify('-1 day'))
                ->setCheckIn($monthStart->modify('-1 day')->setTime(9, 0))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate($monthStart)
                ->setCheckIn($monthStart->setTime(9, 0))
        );
        $employee->addAttendence(
            (new Attendence())->setDate($monthStart->modify('+1 day'))
        );
        $employee->addDayOff(
            (new DayOff())->setDate($monthStart->modify('+2 days'))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate($monthStart->modify('+2 days'))
                ->setCheckIn($monthStart->modify('+2 days')->setTime(9, 0))
        );
        $employee->addAttendence(
            (new Attendence())
                ->setDate($monthStart->modify('+3 days'))
                ->setCheckIn($monthStart->modify('+3 days')->setTime(9, 0))
        );

        self::assertSame(
            50.0,
            $service->getCommitmentPercentage(
                $employee,
                $monthStart,
                $monthStart->modify('+4 days'),
            ),
        );
    }

    function test_get_commitment_percentage_returns_zero_when_range_has_no_scheduled_workdays(): void
    {
        $employee = new Employee();
        $date = new DateTimeImmutable('first day of previous month');
        $employee->addDayOff(
            (new DayOff())->setDate($date)
        );

        self::assertSame(
            0.0,
            $this->createEmployeeService([
                $this->registeredMonth($date),
            ])->getCommitmentPercentage(
                $employee,
                $date,
                $date,
            ),
        );
    }

    function test_get_commitment_percentage_excludes_today_and_future_days(): void
    {
        $today = new DateTimeImmutable('today');
        $yesterday = $today->modify('-1 day');
        $tomorrow = $today->modify('+1 day');
        $registeredMonths = [];
        foreach ([$yesterday, $today, $tomorrow] as $date) {
            $registeredMonths[$date->format('Y-m')] = $this->registeredMonth($date);
        }

        $employee = new Employee();
        foreach ([$yesterday, $today, $tomorrow] as $date) {
            $employee->addAttendence(
                (new Attendence())
                    ->setDate($date)
                    ->setCheckIn($date->setTime(9, 0))
            );
        }

        self::assertSame(
            100.0,
            $this->createEmployeeService(array_values($registeredMonths))->getCommitmentPercentage(
                $employee,
                $yesterday,
                $tomorrow,
            ),
        );
    }

    function test_get_commitment_percentage_excludes_unregistered_months(): void
    {
        $date = (new DateTimeImmutable('first day of previous month'))->modify('-1 month');
        $employee = new Employee();
        $employee->addAttendence(
            (new Attendence())
                ->setDate($date)
                ->setCheckIn($date->setTime(9, 0))
        );

        self::assertSame(
            0.0,
            $this->createEmployeeService()->getCommitmentPercentage($employee, $date, $date),
        );
    }

    /**
     * @param Month[] $registeredMonths
     */
    private function createEmployeeService(array $registeredMonths = []): EmployeeService
    {
        $monthRepository = $this->createStub(MonthRepository::class);
        $monthRepository->method('findAllOrdered')->willReturn($registeredMonths);

        $monthDataFactory = $this->createStub(MonthDataFactory::class);
        $monthDataFactory->method('createFromEmployee')->willReturnCallback(
            function (Employee $employee, int $year, int $month): MonthData {
                $monthData = new MonthData($year, $month);
                foreach ($monthData->getDays() as $dayData) {
                    foreach ($employee->getAttendences() as $attendence) {
                        if ($attendence->getDate() !== null && $attendence->isSameDay($dayData->getDate())) {
                            $dayData->setAttendence($attendence);
                        }
                    }
                    $dayData->setOff($employee->isDayOff($dayData->getDate()));
                }

                return $monthData;
            },
        );

        return new EmployeeService($monthRepository, $monthDataFactory);
    }

    private function registeredMonth(DateTimeImmutable $date): Month
    {
        return (new Month())->setDate($date->modify('first day of this month')->setTime(0, 0));
    }
}
