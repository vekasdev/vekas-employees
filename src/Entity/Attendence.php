<?php

namespace App\Entity;

use App\Repository\AttendenceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendenceRepository::class)]
class Attendence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attendences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $checkIn = null;

    #[ORM\Column(nullable:true)]
    private ?\DateTimeImmutable $checkOut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCheckIn(): ?\DateTimeImmutable
    {
        return $this->checkIn;
    }

    function getWorkingHours() {
        if (isset($this->checkIn) && isset($this->checkOut) ) {
           $time = $this->checkOut->getTimestamp() - $this->checkIn->getTimestamp();
           return $time / 3600;
        }
        return 0;
    }
    public function setCheckIn(\DateTimeImmutable $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    function isCompleted() {
        return $this->getWorkingHours() >= $this->employee->getWorkingHours();
    }

    public function getCheckOut(): ?\DateTimeImmutable
    {
        return $this->checkOut;
    }

    public function setCheckOut(\DateTimeImmutable $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }


    /**
     * check if given day is the same day as the attendence was never mind the time its created
     * @param DateTimeImmutable $dateTime
     * @return bool
     */
    function isSameDay(DateTimeImmutable $dateTime) {
        $givenDate      = $dateTime->setTime(0,0,0,0);
        $attendenceDate = $this->date->setTime(0,0,0,0);
        return $givenDate->getTimestamp() == $attendenceDate->getTimestamp();
    }

}
