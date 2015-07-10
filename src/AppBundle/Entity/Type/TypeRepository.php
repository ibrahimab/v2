<?php
namespace AppBundle\Entity\Type;
use       AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
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
    public function findByPlace(PlaceServiceEntityInterface $place, $limit)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();

        $qb->select('partial t.{id, optimalResidents, maxResidents, quality}, partial a.{id, name, kind, quality}')
           ->leftJoin('t.accommodation', 'a')
           ->where($expr->eq('a.place', ':place'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->setMaxResults($limit)
           ->orderBy('t.searchOrder', 'ASC')
           ->setParameters([

               'place'   => $place,
               'display' => true,
           ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * {@InheritDoc}
     */
    public function countByPlace(PlaceServiceEntityInterface $place)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();

        $qb->select('COUNT(t.id)')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->where($expr->eq('p', ':place'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.weekendSki', ':weekendski'))
           ->setParameters([

               'place'      => $place,
               'display'    => true,
               'weekendski' => false,
           ]);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * {@InheritDoc}
     */
    public function countByRegion(RegionServiceEntityInterface $region)
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

    /**
     * {@InheritDoc}
     */
    public function findById($typeId)
    {
        $qb   = $this->createQueryBuilder('t');
        $expr = $qb->expr();

        $qb->select('t, a, p, r, c, at')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.types', 'at')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where((is_array($typeId) ? $expr->in('t.id', ':type') : $expr->eq('t.id', ':type')))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
           ->andWhere($expr->eq('at.display', ':display'))
           ->setParameters([

               'type'       => $typeId,
               'display'    => true,
               'weekendSki' => false,
           ])
           ->orderBy('at.maxResidents');

        $query = $qb->getQuery();
        return (is_array($typeId) ? $query->getResult() : $query->getSingleResult());
    }
}