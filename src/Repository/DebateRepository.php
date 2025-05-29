<?php

namespace App\Repository;

use App\Entity\Debate;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DebateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debate::class);
    }

    public function getAllDebatsSortedByParticipants(int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('d')
            ->leftJoin('d.camps', 'c')
            ->leftJoin('c.arguments', 'a')
            ->leftJoin('a.votes', 'v')
            ->leftJoin('a.user', 'argUser')
            ->leftJoin('v.user', 'voteUser')
            ->where('d.isValid = true')
            ->groupBy('d.id')
            ->addSelect('COUNT(DISTINCT argUser.id) + COUNT(DISTINCT voteUser.id) AS HIDDEN totalParticipants')
            ->orderBy('totalParticipants', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function calculateStatsForDebat(int $debatId): array
    {
        $em = $this->getEntityManager();

        $camps = $em->createQuery(
            'SELECT c.id FROM App\Entity\Camp c WHERE c.debate = :debatId ORDER BY c.id ASC'
        )
            ->setParameter('debatId', $debatId)
            ->getResult();

        if (count($camps) < 2) {
            return [
                'participants' => 0,
                'votes' => 0,
                'pourcentage_camp_1' => 50,
                'pourcentage_camp_2' => 50,
            ];
        }

        $camp1Id = $camps[0]['id'];
        $camp2Id = $camps[1]['id'];

        $camp1Votes = (int) $em->createQuery(
            'SELECT COUNT(v.id)
        FROM App\Entity\Votes v
        JOIN v.argument a
        JOIN a.camp c
        WHERE c.id = :campId AND a.creationDate IS NOT NULL'
        )
            ->setParameter('campId', $camp1Id)
            ->getSingleScalarResult();

        $camp2Votes = (int) $em->createQuery(
            'SELECT COUNT(v.id)
        FROM App\Entity\Votes v
        JOIN v.argument a
        JOIN a.camp c
        WHERE c.id = :campId AND a.creationDate IS NOT NULL'
        )
            ->setParameter('campId', $camp2Id)
            ->getSingleScalarResult();

        $totalVotes = $camp1Votes + $camp2Votes;
        $pourcentageCamp1 = $totalVotes > 0 ? round(($camp1Votes / $totalVotes) * 100, 1) : 50;
        $pourcentageCamp2 = $totalVotes > 0 ? round(($camp2Votes / $totalVotes) * 100, 1) : 50;

        $argUsers = $em->createQuery(
            'SELECT DISTINCT u.id FROM App\Entity\Argument a
        JOIN a.user u
        JOIN a.camp c
        WHERE c.debate = :debatId'
        )
            ->setParameter('debatId', $debatId)
            ->getArrayResult();

        $voteUsers = $em->createQuery(
            'SELECT DISTINCT u.id FROM App\Entity\Votes v
        JOIN v.user u
        JOIN v.argument a
        JOIN a.camp c
        WHERE c.debate = :debatId'
        )
            ->setParameter('debatId', $debatId)
            ->getArrayResult();

        $userIds = array_unique(array_merge(
            array_column($argUsers, 'id'),
            array_column($voteUsers, 'id')
        ));

        $participants = count($userIds);

        return [
            'participants' => $participants,
            'votes' => $totalVotes,
            'pourcentage_camp_1' => $pourcentageCamp1,
            'pourcentage_camp_2' => $pourcentageCamp2,
        ];
    }



    public function getUserRankingByVotes(): array
    {
        $entityManager = $this->getEntityManager();

        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        $qb = $entityManager->createQueryBuilder();

        $qb->select('u.id', 'u.pseudo', 'COUNT(v.id) AS voteCount')
            ->from('App\Entity\Votes', 'v')
            ->join('v.argument', 'a')
            ->join('a.user', 'u')
            ->where('v.voteDate BETWEEN :startDate AND :endDate')
            ->groupBy('u.id')
            ->orderBy('voteCount', 'DESC')
            ->setMaxResults(3)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return $qb->getQuery()->getResult();
    }
    public function getAllDebatsSortedByDate(int $limit, int $offset): array
    {
        return $this->getEntityManager()->createQuery(
            'SELECT d
         FROM App\Entity\Debate d
         LEFT JOIN d.camps c
         LEFT JOIN c.arguments a WITH a.creationDate IS NOT NULL
         WHERE d.isValid = true
         GROUP BY d.id
         ORDER BY MAX(a.creationDate) DESC'
        )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }
    public function findByAdvancedFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('d')
            ->leftJoin('d.camps', 'c')
            ->leftJoin('c.arguments', 'a')
            ->leftJoin('a.votes', 'v')
            ->leftJoin('d.categories', 'cat')
            ->addSelect('COUNT(v.id) AS HIDDEN voteCount')
            ->andWhere('d.isValid = true')
            ->groupBy('d.id');

        if (!empty($filters['keyword'])) {
            $qb->andWhere('d.nameDebate LIKE :keyword OR d.descriptionDebate LIKE :keyword')
                ->setParameter('keyword', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['minParticipants'])) {
            $qb->having('COUNT(v.id) >= :minParticipants')
                ->setParameter('minParticipants', $filters['minParticipants']);
        }

        if (!empty($filters['categoryIds'])) {
            $qb->andWhere('cat.id IN (:categoryIds)')
                ->setParameter('categoryIds', $filters['categoryIds']);
        }

        if (!empty($filters['startDate'])) {
            $qb->andWhere('d.creationDate >= :startDate')
                ->setParameter('startDate', new \DateTime($filters['startDate']));
        }

        if (!empty($filters['endDate'])) {
            $qb->andWhere('d.creationDate <= :endDate')
                ->setParameter('endDate', new \DateTime($filters['endDate']));
        }

        if (!empty($filters['order']) && $filters['order'] === 'popular') {
            $qb->orderBy('voteCount', 'DESC');
        } else {
            $qb->orderBy('d.creationDate', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }

    public function findRecentDebatesByUser(User $user): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
            SELECT DISTINCT d
            FROM App\Entity\Debate d
            JOIN d.camps c
            LEFT JOIN App\Entity\Argument a WITH a.camp = c
            LEFT JOIN App\Entity\Votes v WITH v.argument = a
            WHERE a.user = :user OR v.user = :user
            AND d.isValid = true
            ORDER BY d.creationDate DESC
        ')->setParameter('user', $user);

        return $query->getResult();
    }

    
    public function getVotesByCamp(Debate $debate): array
    {
        return $this->getEntityManager()->createQuery('
            SELECT c.nameCamp AS camp, COUNT(v.id) AS voteCount
            FROM App\Entity\Votes v
            JOIN v.argument a
            JOIN a.camp c
            WHERE c.debate = :debate
            GROUP BY c.id
        ')->setParameter('debate', $debate)->getResult();
    }

    public function getTopUserByVotes(Debate $debate): ?array
    {
        return $this->getEntityManager()->createQuery('
            SELECT u.pseudo, COUNT(v.id) AS voteCount
            FROM App\Entity\Votes v
            JOIN v.argument a
            JOIN a.user u
            JOIN a.camp c
            JOIN c.debate d
            WHERE d = :debate
            GROUP BY u.id
            ORDER BY voteCount DESC
        ')
        ->setParameter('debate', $debate)
        ->setMaxResults(1)
        ->getOneOrNullResult();
    }

    public function getArgumentsCountByCamp(Debate $debate): array
    {
        return $this->getEntityManager()->createQuery('
            SELECT c.id AS campId, c.nameCamp, COUNT(a.id) AS count
            FROM App\Entity\Argument a
            JOIN a.camp c
            WHERE c.debate = :debate
            GROUP BY c.id
        ')->setParameter('debate', $debate)->getResult();
    }

    public function getTopArgument(Debate $debate): ?array
    {
        return $this->getEntityManager()->createQuery('
            SELECT a, COUNT(v.id) AS voteCount
            FROM App\Entity\Argument a
            LEFT JOIN a.votes v
            JOIN a.camp c
            JOIN c.debate d
            WHERE d = :debate
            GROUP BY a.id
            ORDER BY voteCount DESC
        ')
        ->setParameter('debate', $debate)
        ->setMaxResults(1)
        ->getOneOrNullResult();
    }


}
