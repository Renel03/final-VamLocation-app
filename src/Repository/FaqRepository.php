<?php

namespace App\Repository;

use App\Entity\Faq;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    public function search($query = [], $limit = 24)
    {
        $qb = $this->createQueryBuilder('f');
        $qb ->andWhere('f.status = TRUE')
            ->orderBy('f.createdAt', 'DESC');

        $page = 1;
        if(array_key_exists('page', $query) && is_numeric($query['page']))
            $page = (int) $query['page'];
        return $this->paginate($qb, $limit, $page);
    }
}
