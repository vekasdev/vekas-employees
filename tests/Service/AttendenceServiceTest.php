<?php

namespace App\Tests\Service;

use App\Factory\AttendenceFactory;
use App\Factory\EmployeeFactory;
use App\Service\AttendenceService;
use DateTimeImmutable;
use Override;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class AttendenceServiceTest extends KernelTestCase
{
    use ResetDatabase;
    
    private AttendenceService $attendenceService;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->attendenceService = $container->get(AttendenceService::class);
    }

    function test_check_if_attendence_late()
    {
        $start = new DateTimeImmutable("2000-6-2");
        $start = $start->setTime(9, 0, 0);

        $end = new DateTimeImmutable("2000-6-2");
        $end = $end->setTime(17, 0, 0);

        $checkIn = new DateTimeImmutable();
        $checkIn = $checkIn->setTime(9, 5, 0);

        $emp = EmployeeFactory::createOne([
            "workStartTime" => $start,
            "workEndTime" => $end,
        ]);

        $att = AttendenceFactory::createOne([
            "checkIn" => $checkIn,
            "employee" => $emp
        ]);

        $this->assertTrue($this->attendenceService->isAttendenceLate($att->_real()));

    }

    function test_check_if_attendence_on_time()
    {
        $start = new DateTimeImmutable("2000-6-2");
        $start = $start->setTime(9, 0, 0);

        $end = new DateTimeImmutable("2000-6-2");
        $end = $end->setTime(17, 0, 0);

        $checkIn = new DateTimeImmutable();
        $checkIn = $checkIn->setTime(9, 0, 0);

        $emp = EmployeeFactory::createOne([
            "workStartTime" => $start,
            "workEndTime" => $end,
        ]);

        $att = AttendenceFactory::createOne([
            "checkIn" => $checkIn,
            "employee" => $emp
        ]);

        $this->assertFalse($this->attendenceService->isAttendenceLate($att->_real()));
    }

}
