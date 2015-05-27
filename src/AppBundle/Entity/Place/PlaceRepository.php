<?php
namespace AppBundle\Entity\Place;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceRepositoryInterface;

/**
 * PlaceRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class PlaceRepository extends BaseRepository implements PlaceServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function findByLocaleSeoName($seoName, $locale)
    {
        $field = $this->getLocaleField('seoName', $locale);
        $qb    = $this->createQueryBuilder('p');
        $expr  = $qb->expr();
        
        $qb->select('p, partial c.{id, name, englishName, germanName, startCode}, partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where($expr->eq('p.' . $field, ':seoName'))
           ->andWhere($expr->eq('p.season', ':season'))
           ->andWhere($expr->eq('r.season', ':season'))
           ->setMaxResults(1)
           ->setParameters([
               
               'seoName' => $seoName,
               'season'  => $this->getSeason(),
           ]);

        return $qb->getQuery()->getSingleResult();
    }
    
    /**
     * {@InheritDoc}
     */
    public function findHomepagePlaces(RegionServiceEntityInterface $region, $options = [])
    {
        $limit = self::getOption($options, 'limit', 3);
        $qb    = $this->createQueryBuilder('p');
        $expr  = $qb->expr();

        $qb->select('partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, altitude}')
           ->where($expr->eq('p.showOnHomepage', ':showOnHomepage'))
           ->andWhere($expr->eq('p.region', ':region'))
           ->andWhere($expr->like('p.websites', ':website'))
           ->setMaxResults($limit)
           ->setParameters([
               
               'showOnHomepage' => true,
               'region'         => $region,
               'website'        => '%' . $this->getWebsite() . '%',
           ]);

        return $qb->getQuery()->getResult();
    }
}