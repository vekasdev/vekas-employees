<?php

namespace App\Tests;

use App\Model\DaysScanner;
use PHPUnit\Framework\TestCase;

class DaysScannerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    function test_scan_month_days() {
        $days = DaysScanner::scan("2026","9");
        $this->assertSame(30,count($days));
        $this->assertSame("Tuesday",$days[1]);
    }
}
