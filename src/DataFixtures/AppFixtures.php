<?php

namespace App\DataFixtures;

use App\Entity\DayOff;
use App\Entity\Employee;
use App\Entity\WeeklyDayOff;
use App\Enum\WeekDaysEnum;
use App\Repository\EmployeeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $wdayoff = new WeeklyDayOff();
        $wdayoff->setDay(WeekDaysEnum::FRIDAY);

        $manager->persist($wdayoff);
        $manager->flush();
    }
}
