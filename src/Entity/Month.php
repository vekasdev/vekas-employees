<?php

namespace App\Entity;

use App\Repository\MonthRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthRepository::class)]
class Month
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: false)]
    private ?bool $isDayOffPopulated = false;


    function __construct()
    {
        $this->isDayOffPopulated = false;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function isDayOffPopulated(): ?bool
    {
        return $this->isDayOffPopulated;
    }

    public function setIsDayOffPopulated(?bool $isDayOffPopulated): static
    {
        $this->isDayOffPopulated = $isDayOffPopulated;

        return $this;
    }
}
