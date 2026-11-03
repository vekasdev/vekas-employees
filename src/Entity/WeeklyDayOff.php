<?php

namespace App\Entity;

use App\Enum\WeekDaysEnum;
use App\Repository\WeeklyDayOffRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeeklyDayOffRepository::class)]
class WeeklyDayOff
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: WeekDaysEnum::class,unique:true)]
    private ?WeekDaysEnum $day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?WeekDaysEnum
    {
        return $this->day;
    }

    public function setDay(WeekDaysEnum $day): static
    {
        $this->day = $day;

        return $this;
    }

}
