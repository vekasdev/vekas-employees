<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $employeeNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    /**
     * @var Collection<int, Attendence>
     */
    #[ORM\OneToMany(targetEntity: Attendence::class, mappedBy: 'employee', orphanRemoval: true ,cascade:["PERSIST"])]
    private Collection $attendences;

    #[ORM\Column()]
    private int $workingHours = 8;

    /**
     * @var Collection<int, DayOff>
     */
    #[ORM\ManyToMany(targetEntity: DayOff::class, mappedBy: 'employees',cascade:["PERSIST"])]
    private Collection $dayOffs;

    public function __construct()
    {
        $this->attendences = new ArrayCollection();
        $this->employeeNumber = uniqid();
        $this->dayOffs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeNumber(): ?string
    {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber(string $employeeNumber): static
    {
        $this->employeeNumber = $employeeNumber;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Attendence>
     */
    public function getAttendences(): Collection
    {
        return $this->attendences;
    }

    public function addAttendence(Attendence $attendence): static
    {
        if (!$this->attendences->contains($attendence)) {
            $this->attendences->add($attendence);
            $attendence->setEmployee($this);
        }

        return $this;
    }

    public function removeAttendence(Attendence $attendence): static
    {
        if ($this->attendences->removeElement($attendence)) {
            // set the owning side to null (unless already changed)
            if ($attendence->getEmployee() === $this) {
                $attendence->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of workingHours
     */ 
    public function getWorkingHours()
    {
        return $this->workingHours;
    }

    /**
     * Set the value of workingHours
     *
     * @return  self
     */ 
    public function setWorkingHours($workingHours)
    {
        $this->workingHours = $workingHours;

        return $this;
    }

    /**
     * @return Collection<int, DayOff>
     */
    public function getDayOffs(): Collection
    {
        return $this->dayOffs;
    }

    public function addDayOff(DayOff $dayOff): static
    {
        if (!$this->dayOffs->contains($dayOff)) {
            $this->dayOffs->add($dayOff);
            $dayOff->addEmployee($this);
        }

        return $this;
    }

    public function removeDayOff(DayOff $dayOff): static
    {
        if ($this->dayOffs->removeElement($dayOff)) {
            $dayOff->removeEmployee($this);
        }

        return $this;
    }

    function isDayOff(DateTimeImmutable $day) {
        foreach($this->dayOffs as $dayOff) {
            $res = isSameDay($day,$dayOff->getDate());
            if ($res) return true;
        }
        return false;
    }

}
