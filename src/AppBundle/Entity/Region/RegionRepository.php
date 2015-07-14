<?php
namespace AppBundle\Entity\Region;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceRepositoryInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       Doctrine\ORM\EntityRepository;
use       Doctrine\ORM\Query\Expr;

/**
 * RegionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegionRepository extends BaseRepository implements RegionServiceRepositoryInterface
{
    /**
     * {@InheritDoc}
     */
    public function findByLocaleName($name, $locale)
    {
        return $this->findByLocaleField('name', $name, $locale);
    }

    /**
     * {@InheritDoc}
     */
    public function findByLocaleSeoName($seoName, $locale)
    {
        return $this->findByLocaleField('seoName', $seoName, $locale);
    }

    /**
     * {@InheritDoc}
     */
    public function findByLocaleField($field, $value, $locale)
    {
        $field = $this->getLocaleField($field, $locale);
        $qb    = $this->getEntityManager()->createQueryBuilder();
        $expr  = $qb->expr();

        $qb->select('r, partial c.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, startCode}, partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, altitude, distanceFromUtrecht}')
           ->from('AppBundle\Entity\Place\Place', 'p')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where($expr->eq('r.' . $field, ':fieldName'))
           ->andWhere($expr->eq('p.season', ':season'))
           ->andWhere($expr->eq('r.season', ':season'))
           ->groupBy('p.id')
           ->setParameters([

               'fieldName' => $value,
               'season'    => $this->getSeason(),
           ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * {@InheritDoc}
     */
    public function findHomepageRegions($options = [])
    {
        $limit = self::getOption($options, 'limit', 1);
        $qb    = $this->createQueryBuilder('r');
        $expr  = $qb->expr();

        $qb->select('partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, totalSlopesDistance, minimumAltitude, maximumAltitude}, RAND() AS HIDDEN rand_seed')
           ->where($expr->eq('r.showOnHomepage', ':showOnHomepage'))
           ->andWhere($expr->gt('FIND_IN_SET(:website, r.websites)', 0))
           ->setParameters([

               'showOnHomepage' => true,
               'website'        => $this->getWebsite()
           ])
           ->setMaxResults($limit)
           ->orderBy('rand_seed');

        return $qb->getQuery()->getResult();
    }
}