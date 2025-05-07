<?php

namespace App\Repository;

use App\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Votes>
 */
class VotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Votes::class);
    }

    /**
     * @return Votes[] Returns an array of Votes objects
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :user')
            ->setParameter('user', $user)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $user
     * @param $debate
     * @return Integer Returns the numbers of vote for one user and one debate
     */
    public function countByUserAndDebate($user, $debate): int
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT COUNT(v.id)
            FROM App\Entity\Votes v
            INNER JOIN v.argument a
            INNER JOIN a.camp c
            INNER JOIN c.debate d
            WHERE v.user = :user
            AND d.id = :debate'
        )->setParameter('user', $user)
        ->setParameter('debate', $debate)
        ->getSingleScalarResult();
    }
    //    /**
    //     * @return Votes[] Returns an array of Votes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Votes
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
