<?php

namespace App\Command;

use App\Repository\AttendenceRepository;
use App\Repository\EmployeeRepository;
use App\Service\AttendenceService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-attendence',
    description: 'Add a short description for your command',
)]
class CreateAttendenceCommand{

    function __construct(
        private AttendenceRepository $attendence_repository,
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $em
    )
    {
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('the employee to assign attendence to')] int $employee,
        #[Argument('the date of the attendence')] string $date = '',
        #[Argument('check in time')] string $checkIn = '',
        #[Argument('check out time')] string $checkOut = '',
    ): int {

        $checkIn = new DateTimeImmutable($date." ".$checkIn);
        $checkOut = new DateTimeImmutable($date." ".$checkOut);
        $date = new DateTimeImmutable($date);

        $attendence = $this->attendence_repository->create();
        $attendence->setEmployee($this->employeeRepository->find($employee));
        $attendence->setCheckIn($checkIn);
        $attendence->setCheckOut($checkOut);
        $attendence->setDate($date);

        $this->em->persist($attendence);
        $this->em->flush();
        
        $io->success("attendence created successfully ");

        return Command::SUCCESS;
    }
}
