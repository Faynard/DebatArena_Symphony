<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserRankingByVotes(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        // Classement global
        $sqlGlobal = "
            SELECT u.id, COUNT(v.id) AS total_votes,
                RANK() OVER (ORDER BY COUNT(v.id) DESC) AS rank_global
            FROM user u
            LEFT JOIN votes v ON v.user_id = u.id
            GROUP BY u.id
        ";

        // Classement du mois
        $sqlMonth = "
            SELECT u.id, COUNT(v.id) AS month_votes,
                RANK() OVER (ORDER BY COUNT(v.id) DESC) AS rank_month
            FROM user u
            LEFT JOIN votes v ON v.user_id = u.id
            WHERE v.vote_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY u.id
        ";

        $stmtGlobal = $conn->prepare($sqlGlobal);
        $resultGlobal = $stmtGlobal->executeQuery()->fetchAllAssociative();

        $stmtMonth = $conn->prepare($sqlMonth);
        $resultMonth = $stmtMonth->executeQuery()->fetchAllAssociative();

        $userId = $user->getId();

        $rankGlobal = null;
        foreach ($resultGlobal as $row) {
            if ((int) $row['id'] === $userId) {
                $rankGlobal = (int) $row['rank_global'];
                break;
            }
        }

        $rankMonth = null;
        foreach ($resultMonth as $row) {
            if ((int) $row['id'] === $userId) {
                $rankMonth = (int) $row['rank_month'];
                break;
            }
        }

        return [
            'rank_global' => $rankGlobal ?? 'Non classé',
            'rank_month' => $rankMonth ?? 'Non classé',
        ];
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
