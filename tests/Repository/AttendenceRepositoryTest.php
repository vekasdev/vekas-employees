<?php

namespace App\Tests\Repository;

use App\Entity\Attendence;
use App\Factory\AttendenceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;
use App\Repository\AttendenceRepository;

class AttendenceRepositoryTest extends KernelTestCase
{

    use ResetDatabase;  
    private ContainerInterface $containerInterface;
    private EntityManagerInterface $em;

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->containerInterface = $kernel->getContainer();

        $this->em = $this->containerInterface->get("doctrine")->getManager();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    // function test_get_by_month() {


    //     // load data
    //     AttendenceFactory::createMany(3,[
            
    //     ]);


    //     /** @var AttendenceRepository */    
    //     $repo = $this->em->getRepository(Attendence::class);
    //     $repo->getByMonth(0,2026);
    // }


}
