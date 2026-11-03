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


    function test_get_working_hours(){
        // 8 hours
        $employee = new Employee;
        $employee->setWorkStartTime((new DateTimeImmutable())->setTime(9,0,0));
        $employee->setWorkEndTime((new DateTimeImmutable())->setTime(17,0,0));
        $this->assertSame(8,$employee->getWorkingHours());

        // 10 hours
        $employee2 = new Employee;
        $employee2->setWorkStartTime((new DateTimeImmutable())->setTime(9,0,0));
        $employee2->setWorkEndTime((new DateTimeImmutable())->setTime(19,0,0));
        $this->assertSame(10,$employee2->getWorkingHours());

    }

}
