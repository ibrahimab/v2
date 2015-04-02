<?php
namespace AppBundle\Entity\Type;
use       AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;
use       AppBundle\Entity\BaseRepository;

/**
 * TypeRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class TypeRepository extends BaseRepository implements TypeServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function findByPlace($place)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();
        
        $qb->select('partial t.{id, name}')
           ->leftJoin('t.accommodation', 'a')
           ->where($expr->eq('a.place', ':place'))
           ->setParameter('place', $place);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * {@InheritDoc}
     */
    public function countByRegion($region)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();
        
        $qb->select('p.id as placeId, COUNT(t.id) AS typesCount')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->where($expr->eq('r', ':region'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.weekendSki', ':weekendski'))
           ->groupBy('p.id')
           ->setParameters([
               
               'region'     => $region,
               'display'    => true,
               'weekendski' => false,
           ]);
        
        $results = $qb->getQuery()->getResult();
        
        return array_map('intval', array_column($results, 'typesCount', 'placeId'));
    }
    
    /**
     * {@InheritDoc}
     */
    public function countByRegions($regions)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();
        
        $qb->select('r.id as regionId, COUNT(t.id) AS typesCount')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->groupBy('r.id')
           ->where($expr->in('r', ':regions'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.weekendSki', ':weekendski'))
           ->setParameters([
               
               'regions'    => $regions,
               'display'    => true,
               'weekendski' => false,
           ]);
        
        $results = $qb->getQuery()->getResult();

        return array_map('intval', array_column($results, 'typesCount', 'regionId'));
    }
}