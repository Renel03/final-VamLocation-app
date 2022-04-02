<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function search($query = [], $limit = 24)
    {
        $qb = $this->createQueryBuilder('p');

        if(array_key_exists('type', $query))
            $qb     ->andWhere("p.type IN(:type)")
                    ->setParameter('type', $query['type']);

        if(array_key_exists('state', $query))
            $qb     ->andWhere("p.state IN(:state)")
                    ->setParameter('state', $query['state']);

        if(array_key_exists('fuel', $query))
            $qb     ->andWhere("p.fuel IN(:fuel)")
                    ->setParameter('fuel', $query['fuel']);
        
        if(array_key_exists('trademark', $query))
            $qb     ->join('p.trademark', 't')
                    ->andWhere("t.slug IN(:trademark)")
                    ->setParameter('trademark', $query['trademark']);
        
        if(array_key_exists('model', $query))
            $qb     ->join('p.model', 'm')
                    ->andWhere("m.slug IN(:model)")
                    ->setParameter('model', $query['model']);

        if(array_key_exists('version', $query))
            $qb     ->join('p.version', 'v')
                    ->andWhere("v.slug IN(:version)")
                    ->setParameter('version', $query['version']);

        if(array_key_exists('priceMin', $query)){
            $qb     ->andWhere("p.price >= :priceMin")
                    ->setParameter('priceMin', $query['priceMin']);
        }
        
        if(array_key_exists('priceMax', $query)){
            $qb     ->andWhere("p.price <= :priceMax")
                    ->setParameter('priceMax', $query['priceMax']);
        }
        
        if(array_key_exists('mileageMin', $query)){
            $qb     ->andWhere("p.mileage >= :mileageMin")
                    ->setParameter('mileageMin', $query['mileageMin']);
        }
        
        if(array_key_exists('mileageMax', $query)){
            $qb     ->andWhere("p.mileage <= :mileageMax")
                    ->setParameter('mileageMax', $query['mileageMax']);
        }
        
        if(array_key_exists('yearMin', $query)){
            $qb     ->andWhere("p.year >= :yearMin")
                    ->setParameter('yearMin', $query['yearMin']);
        }
        
        if(array_key_exists('yearMax', $query)){
            $qb     ->andWhere("p.year <= :yearMax")
                    ->setParameter('yearMax', $query['yearMax']);
        }
        
        if(array_key_exists('speedType', $query)){
            $qb     ->andWhere("p.speedType = :speedType")
                    ->setParameter('speedType', $query['speedType']);
        }

        $qb ->andWhere('p.status != 0')
            ->orderBy('p.createdAt', 'DESC')
            ->addOrderBy('p.updatedAt', 'DESC');

        $page = 1;
        if(array_key_exists('page', $query) && is_numeric($query['page']))
            $page = (int) $query['page'];
        return $this->paginate($qb, $limit, $page);
    }
}
