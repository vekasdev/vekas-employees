<?php

namespace App\Repository;

use App\Entity\DayOff;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DayOff>
 */
class DayOffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayOff::class);
    }


    function findByDate(DateTimeImmutable $dateTime) {
        $start = $dateTime->setTime(0,0,0,0);
        $end = $dateTime->modify("+1 day");
        $qb = $this->createQueryBuilder("d");
        $qb = $qb->andWhere("d.date >= :start")
            ->andWhere("d.date <= :end")
            ->setParameter("start",$start)
            ->setParameter("end",$end);
        return $qb->getQuery()->getResult();
    }
    
    function create(DateTimeImmutable $date) {
        $dayOff = new DayOff();
        $dayOff->setDate($date);
        return $dayOff;
    }

    //    /**
    //     * @return DayOff[] Returns an array of DayOff objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DayOff
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
