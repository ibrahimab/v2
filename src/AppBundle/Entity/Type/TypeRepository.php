<?php
namespace AppBundle\Entity\Type;

use AppBundle\Service\Api\Type\TypeService;
use AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;
use AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use AppBundle\Entity\BaseRepository;
use AppBundle\Entity\Accommodation\Accommodation;
use PDO;

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
           ->andWhere($expr->eq('a.weekendSki', ':weekendski'))
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, a.websites)', 0))
           ->andWhere($expr->eq('a.season', ':season'))
           ->setMaxResults($limit)
           ->orderBy('t.searchOrder', 'ASC')
           ->setParameters([

               'place'      => $place,
               'display'    => true,
               'weekendski' => false,
               'website'    => $this->getWebsite(),
               'season'     => $this->getSeason(),
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
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, a.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, p.websites)', 0))
           ->andWhere($expr->eq('a.season', ':season'))
           ->andWhere($expr->eq('p.season', ':season'))
           ->setParameters([

               'place'      => $place,
               'display'    => true,
               'weekendski' => false,
               'website'    => $this->getWebsite(),
               'season'     => $this->getSeason(),
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
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, a.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, p.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, r.websites)', 0))
           ->andWhere($expr->eq('a.season', ':season'))
           ->andWhere($expr->eq('p.season', ':season'))
           ->andWhere($expr->eq('r.season', ':season'))
           ->groupBy('p.id')
           ->setParameters([

               'region'     => $region,
               'display'    => true,
               'weekendski' => false,
               'website'    => $this->getWebsite(),
               'season'     => $this->getSeason(),
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
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, a.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, p.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, r.websites)', 0))
           ->andWhere($expr->eq('a.season', ':season'))
           ->andWhere($expr->eq('p.season', ':season'))
           ->andWhere($expr->eq('r.season', ':season'))
           ->setParameters([

               'regions'    => $regions,
               'display'    => true,
               'weekendski' => false,
               'website'    => $this->getWebsite(),
               'season'     => $this->getSeason(),
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
           ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
           ->andWhere($expr->eq('at.display', ':display'))
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, a.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, at.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, p.websites)', 0))
           ->andWhere($expr->gt('FIND_IN_SET(:website, r.websites)', 0))
           ->andWhere($expr->eq('a.season',  ':season'))
           ->andWhere($expr->eq('p.season',  ':season'))
           ->andWhere($expr->eq('r.season',  ':season'))
           ->setParameters([

               'type'       => $typeId,
               'weekendSki' => false,
               'display'    => true,
               'website'    => $this->getWebsite(),
               'season'     => $this->getSeason(),
           ])
           ->orderBy('at.maxResidents');

        $query = $qb->getQuery();
        return (is_array($typeId) ? $query->getResult() : $query->getSingleResult());
    }

    /**
     * {@InheritDoc}
     */
    public function getTypeById($typeId)
    {
        $db     = $this->getEntityManager()->getConnection();
        $locale = $this->getLocale();
        $typeId = (!is_array($typeId) ? [$typeId] : $typeId);

        $localeField = function($field) use ($locale) {
            return ($field . ($locale === 'nl' ? '' : ('_' . $locale)));
        };

        $type_name    = $localeField('t.naam');
        $region_name  = $localeField('r.naam');
        $place_name   = $localeField('p.naam');
        $country_name = $localeField('c.naam');

        $query = "SELECT t.type_id, a.accommodatie_id AS accommodation_id, r.skigebied_id AS region_id, p.plaats_id AS place_id, c.land_id AS country_id,
                         c.begincode AS country_code, t.kwaliteit AS type_quality, a.kwaliteit AS accommodation_quality, t.optimaalaantalpersonen AS optimal_persons,
                         t.maxaantalpersonen AS max_persons, IF(a.toonper = 1, 'arrangement', 'accommodation') AS type, t.slaapkamers AS bedrooms, t.badkamers AS bathrooms,
                         a.toonper AS accommodation_show, t.kenmerken AS type_features, a.kenmerken AS accommodation_features, p.kenmerken AS place_features,
                         {$type_name} AS type_name, a.naam AS accommodation_name, {$region_name} AS region_name, {$place_name} AS place_name, {$country_name} AS country_name,
                         a.soortaccommodatie AS accommodation_kind
                  FROM   type t, accommodatie a, skigebied r, plaats p, land c
                  WHERE  t.accommodatie_id  = a.accommodatie_id
                  AND    a.plaats_id        = p.plaats_id
                  AND    p.skigebied_id     = r.skigebied_id
                  AND    p.land_id          = c.land_id
                  AND    a.weekendski       = :weekendski
                  AND    FIND_IN_SET(:website, t.websites) > 0
                  AND    FIND_IN_SET(:website, a.websites) > 0
                  AND    FIND_IN_SET(:website, r.websites) > 0
                  AND    FIND_IN_SET(:website, p.websites) > 0
                  AND    a.wzt              = :season
                  AND    r.wzt              = :season
                  AND    p.wzt              = :season
                  AND    t.type_id " . (' IN (' . implode(', ', $typeId) . ')');

        $statement = $db->prepare($query);
        $statement->bindValue('weekendski', false);
        $statement->bindValue('website', $this->getWebsite());
        $statement->bindValue('season', $this->getSeason());
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) === 0) {
            throw new NoResultException('Could not find Type(s) with ID=' . implode(', ', $typeId));
        }

        $records = TypeService::parseResults($results);

        return (count($typeId) === 1 ? current($records) : $records);
    }
}
