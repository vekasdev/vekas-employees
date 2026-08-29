<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\AttendenceRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AttendenceService {
    function __construct(
        private AttendenceRepository $attendenceRepository,
        private EntityManagerInterface $entityManager
    )
    {}
    
    function checkIn(Employee $employee) {
        $attendence = $this->getTodayAttendence($employee);
        if ($attendence) {
            if($attendence->getCheckIn() != null) {
                return;
            }
        }else {
            // create attendence of this date
            $attendence = $this->attendenceRepository->create();
        }
        $attendence->setCheckIn(new DateTimeImmutable());
        $employee->addAttendence($attendence);
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

    function checkOut(Employee $employee) {
        $attendence = $this->getTodayAttendence($employee);
        if ($attendence) {
            if(
                $attendence->getCheckOut() != null ||
                $attendence->getCheckIn() == null
            ) {
                return;
            }
        }else {
            return; // if there is no attendence , when you do check out you must check in first
        }
        $attendence->setCheckOut(new DateTimeImmutable());
        $employee->addAttendence($attendence);
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

    function getTodayAttendence(Employee $employee) {
        $attendences = $employee->getAttendences();
        $currentDay = new DateTimeImmutable();
        foreach($attendences as $attendence) {
            if ( $attendence->getDate()->format("Y-m-d") === $currentDay->format("Y-m-d") ) {
                return $attendence;
            }
        }
        // or create new one
        $attendence = $this->attendenceRepository->create();
        $employee->addAttendence($attendence);
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        return $attendence;
    }

}