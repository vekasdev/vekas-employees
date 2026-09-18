<?php

namespace App\Service;

use App\Entity\Month;
use App\Repository\MonthRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class MonthService {

    function __construct(
        private MonthRepository $monthRepository,
        private EntityManagerInterface $em
    )
    {}

        /**
     * create a day offs according to the WeeklyDayOff entity
     * @return bool|mixed|Month
     */
    function initMonth() : Month {
        $now = new DateTimeImmutable();
        $month = $this->monthRepository->findByDate($now);
        if (count($month)) {
            return end($month);
        }
        $month = $this->monthRepository->create(new DateTimeImmutable());
        $this->em->persist($month);
        $this->em->flush();
        return $month;
    }


    function getActivatedMonths() {
        $months = $this->monthRepository->findAllOrdered();
        $data = [];
        foreach($months as $month) {
            $year = $month->getDate()->format("Y");
            $data[$year][] = (int) $month->getDate()->format("m");
        }
        return $data;
    }
    
}