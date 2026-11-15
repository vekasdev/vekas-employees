<?php

namespace App\Tests\Service;

use App\Enum\WeekDaysEnum;
use App\Factory\EmployeeFactory;
use App\Factory\WeeklyDayOffFactory;
use App\Repository\DayOffRepository;
use App\Repository\MonthRepository;
use App\Service\MonthService;
use App\Service\ReportService;
use App\Service\WorkdayService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class WorkdayServiceTest extends KernelTestCase
{
    use ResetDatabase;

    private WorkdayService $workdayService ;
    private EntityManagerInterface $em;
    private MonthService $monthService;
    private ContainerInterface $container;

    #[Override]
    function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->em = $this->container->get("doctrine")->getManager();
        $this->monthService = $this->container->get(MonthService::class);
        $this->workdayService = $this->container->get(WorkdayService::class);
    }

    function test_init_month() {
        $fridayOff = WeeklyDayOffFactory::createOne([
            "day" => WeekDaysEnum::FRIDAY
        ]);
        $this->monthService->initMonth();
        $monthRepo = $this->container->get(MonthRepository::class);
        $month = $monthRepo->findAll()[0];
        $this->assertNotNull($month->getDate());
    }

    function test_update_month_day_offs() {
        
        $dayOffRepo = $this->container->get(DayOffRepository::class);

        $currentMonthDayOffs = $this->countWeekdayInCurrentMonth(WeekDaysEnum::FRIDAY->value);

        $fridayOff = WeeklyDayOffFactory::createOne([
            "day" => WeekDaysEnum::FRIDAY
        ]);

        $employee1 = EmployeeFactory::createOne();
        $employee2 = EmployeeFactory::createOne();
        $employee3 = EmployeeFactory::createOne();

        $this->monthService->initMonth();

        $this->workdayService->updateMonthDayOff();

        $daysOff = $dayOffRepo->findAll();

        $this->assertSame($currentMonthDayOffs,$employee1->getDayOffs()->count());
        $this->assertSame($currentMonthDayOffs,$employee1->getDayOffs()->count());
        $this->assertSame($currentMonthDayOffs,$employee1->getDayOffs()->count());

    }

    private function countWeekdayInCurrentMonth(string $day): int
    {
        $start = new DateTime('first day of this month');
        $end = new DateTime('last day of this month');
        $count = 0;
        while ($start <= $end) {
            if ($start->format('l') === ucfirst(strtolower($day))) {
                $count++;
            }
            $start->modify('+1 day');
        }
        return $count;
    }
    

}