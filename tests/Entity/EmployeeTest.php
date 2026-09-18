<?php

namespace App\Tests\Entity;

use App\Entity\DayOff;
use App\Entity\Employee;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmployeeTest extends KernelTestCase
{
    function test_is_day_off() {
        $employee = new Employee();

        $dayOff = new DayOff();
        $dayOff->setDate(new DateTimeImmutable());

        $dayOff2 = new DayOff();
        $dayOff2->setDate(new DateTimeImmutable("2023-3-4"));


        $employee->addDayOff($dayOff);
        $employee->addDayOff($dayOff2);

        $this->assertTrue($employee->isDayOff(new DateTimeImmutable("+1 minute")));
        $this->assertTrue($employee->isDayOff(new DateTimeImmutable()));
        $this->assertFalse($employee->isDayOff(new DateTimeImmutable("+1 day")));
        $this->assertTrue($employee->isDayOff(new DateTimeImmutable("2023-3-4")));

    }
}
