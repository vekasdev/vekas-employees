<?php

namespace App\Tests\App\Service;

use App\Factory\AttendenceFactory;
use App\Factory\EmployeeFactory;
use App\Repository\EmployeeRepository;
use App\Service\ReportService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReportsServiceTest extends KernelTestCase
{

    use ResetDatabase;
    private ReportService $reportsService ;
    private EntityManagerInterface $em;

    private ContainerInterface $container;

    #[Override]
    function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $this->em = $container->get("doctrine")->getManager();
        $this->container = $container;
        $this->reportsService = $container->get(ReportService::class);
        parent::setUp();
    }

    function test_get_employee_data_for_specific_month(){
        $data = $this->reportsService->getEmployeeData($this->dataLoad(),9,2026);     
        $this->assertNotNull($data[0]->getDate());
        $this->assertNotNull($data[0]->getAttendence());
        $this->assertTrue(true);
    }

    function dataLoad(){    
        $employee = EmployeeFactory::createOne();
        $attendence = AttendenceFactory::createMany(3,[
            "employee" => $employee->_real(),
            "date" => new DateTimeImmutable("2026-9-1")
        ]);

        return $employee->_real();
    }

}
