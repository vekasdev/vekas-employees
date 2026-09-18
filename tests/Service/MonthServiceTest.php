<?php

namespace App\Tests\Service;

use App\Factory\MonthFactory;
use App\Service\MonthService;
use App\Service\WorkdayService;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class MonthServiceTest extends KernelTestCase
{
    use ResetDatabase;

    private EntityManagerInterface $em;
    private ContainerInterface $container;

    private MonthService $monthService;

    #[Override]
    function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->em = $this->container->get("doctrine")->getManager();
        $this->monthService = $this->container->get(MonthService::class);
    }

    function test_get_activated_months_by_each_year() {
        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2026-1-1")
            ]
        );
        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2026-2-1")
            ]
        );
        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2026-3-1")
            ]
        );

        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2025-1-1")
            ]
        );
        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2025-2-1")
            ]
        );
        MonthFactory::createOne(
            [
                "date" => new DateTimeImmutable("2025-3-1")
            ]
        );


        
        $months = $this->monthService->getActivatedMonths();

        $this->assertIsArray($months);
        $this->assertCount(2,new ArrayCollection( $months));
        $this->assertCount(3,new ArrayCollection($months["2025"]));
        $this->assertCount(3,new ArrayCollection($months["2026"]));

    }

}
