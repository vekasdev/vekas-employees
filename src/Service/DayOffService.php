<?php


namespace App\Service;

use App\Entity\Employee;
use App\Repository\DayOffRepository;
use App\Repository\EmployeeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class DayOffService {
    function __construct(
        private DayOffRepository $dayOffRepository,
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $em
    )
    {}

    /**
     * create a day off and populate it to all employees registerd at the system
     * @param DateTimeImmutable $day
     * @return \App\Entity\DayOff
     */
    function create(DateTimeImmutable $day) {
        $dayOff = $this->dayOffRepository->create($day);
        
        /** @var Employee[] */
        $employees = $this->employeeRepository->findAll();

        foreach($employees as $employee) {
            $employee->addDayOff($dayOff);
            $this->em->persist($employee);
        }
        $this->em->flush();
        return $dayOff;
    }
}