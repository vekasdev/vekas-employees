<?php

namespace App\Repository;

use App\Entity\Attendence;
use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attendence>
 */
class AttendenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendence::class);
    }

    function create() {
        $attendence = new Attendence();
        $attendence->setDate(new DateTimeImmutable());
        return $attendence;
    }


    /**
     * get attendenecies data by a specific month
     * and year and by a specific employee if provided
     * @param int $month
     * @param int $year
     * @param Employee|null $employee
     * @return array<Attendence>
     */
    function getByMonth(int $month,int $year,Employee | null $employee = null) {
        
        $start = new DateTimeImmutable(
            sprintf("%d-%d-1 0:0:0",$year,$month)
        );

        $end = $start->modify("+1 month");

        $qb = $this->createQueryBuilder("a");

        $qb = $qb->select("a")
        ->andWhere("a.date >= :start")
        ->andWhere("a.date < :end ")
        ->setParameter("start",$start)
        ->setParameter("end",$end)
        ->orderBy("a.date","ASC");

        if ($employee) {
            $qb->andWhere("a.employee = :employee ")
            ->setParameter("employee",$employee);
        }

        return $qb->getQuery()->getResult();
    }

    function getReport(int $month,int $year,Employee | null $employee = null) {

    }


    //    /**
    //     * @return Attendence[] Returns an array of Attendence objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Attendence
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
