<?php

namespace App\Tests;

use App\Model\DayData;
use App\Model\DaysScanner;
use App\Model\MonthData;
use Override;
use PHPUnit\Framework\TestCase;

class MonthDataTest extends TestCase
{

    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    function test_create_and_init() {
        $month = new MonthData(2026,9);
        $days = $month->getDays();
        $this->assertSame(30,count($days));
        $this->assertInstanceOf(DayData::class,$days[0]);
    }

}
