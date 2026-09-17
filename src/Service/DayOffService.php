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