<?php

namespace App\Repository;

use App\Entity\Month;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Month>
 */
class MonthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Month::class);
    }

    function findByDate(DateTimeImmutable $date) {
        $start = $date->modify("first day of this month")->setTime(0,0);
        $end = $start->modify("+1 month");
        $qb = $this->createQueryBuilder("m");
        return $qb->andWhere("m.date >= :start")
        ->andWhere("m.date <= :end")
        ->setParameter("start",$start)
        ->setParameter("end",$end)
        ->getQuery()
        ->getResult();
    }

    function create(DateTimeImmutable $date) {
        $month = new Month();
        $month->setDate($date);
        return $month;
    }
    

    /**
     * 
     * @return Month[]
     */
    function findAllOrdered() {
        $qb = $this->createQueryBuilder("m");
       return  $qb->orderBy("m.date")
        ->getQuery()->getResult();
    }

    //    /**
    //     * @return Month[] Returns an array of Month objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Month
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
