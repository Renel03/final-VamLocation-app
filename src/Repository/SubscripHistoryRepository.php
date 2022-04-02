<?php

namespace App\Repository;

use App\Entity\SubscripHistory;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubscripHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscripHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscripHistory[]    findAll()
 * @method SubscripHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscripHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscripHistory::class);
    }

    // /**
    //  * @return SubscripHistory[] Returns an array of SubscripHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubscripHistory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
