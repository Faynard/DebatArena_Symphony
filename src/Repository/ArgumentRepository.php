<?php

namespace App\Repository;

use App\Entity\Argument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Argument>
 */
class ArgumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Argument::class);
    }

    /**
     * @return Argument[] Returns an array of Argument objects
     */
    public function findMainValidatedArgumentByCamp($camp): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.camp = :camp')
            ->andWhere('a.validationDate <= :date')
            ->setParameter('camp', $camp)
            ->setParameter('date', new \DateTime())
            ->orderBy('a.validationDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    //    /**
    //     * @return Argument[] Returns an array of Argument objects
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

    //    public function findOneBySomeField($value): ?Argument
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
