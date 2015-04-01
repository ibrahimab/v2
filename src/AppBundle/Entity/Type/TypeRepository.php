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
    public function countByRegion($region)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();
        
        $qb->select('COUNT(t.id) AS typesCount')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->where($expr->eq('r', ':region'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.weekendSki', ':weekendski'))
           ->setParameters([
               
               'region'     => $region,
               'display'    => true,
               'weekendski' => false,
           ]);
        
        $result = $qb->getQuery()->getSingleScalarResult();
        return intval($result);
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