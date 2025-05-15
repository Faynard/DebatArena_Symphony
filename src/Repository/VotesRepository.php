<?php

namespace App\Repository;

use App\Entity\Votes;
use App\Entity\User;
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
    public function findByUserAndDebate($user, $debate): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT v
            FROM App\Entity\Votes v
            INNER JOIN v.argument a
            INNER JOIN a.camp c
            WHERE v.user = :user
            AND c.debate = :debate'
        )->setParameter('user', $user)
            ->setParameter('debate', $debate)
            ->getResult();
    }

    public function countVotesByUser(User $user): int
    {

        $test =  $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();


        return $test;
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
