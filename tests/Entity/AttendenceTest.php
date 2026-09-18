<?php

namespace App\Tests\Entity;

use App\Entity\Attendence;
use App\Factory\AttendenceFactory;
use App\Factory\EmployeeFactory;
use DateTimeImmutable;
use Override;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttendenceTest extends KernelTestCase
{


    #[Override]
    function setUp(): void
    {
        $kernel = self::bootKernel();


        parent::setUp();
    }

    function testIsCompleteFunction() {
        $employee = EmployeeFactory::createOne([
            "workingHours" => 8
        ]);

        $attendence = AttendenceFactory::createOne([
            "employee" => $employee,
            "checkIn" => (new DateTimeImmutable("2026-2-3"))->setTime(9,0,0),
            "checkOut" => (new DateTimeImmutable("2026-2-3"))->setTime(17,0,0)
        ]);

        $this->assertTrue($attendence->_real()->isCompleted());

    }
  
    
}
