<?php
namespace AppBundle\Entity\Place;

use AppBundle\Service\Api\Type\TypeService;
use AppBundle\Entity\BaseRepository;
use AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use AppBundle\Service\Api\Place\PlaceServiceRepositoryInterface;
use AppBundle\Service\Api\Search\Params;
use AppBundle\Service\Api\Search\Result\Sorter;
use AppBundle\Service\Api\PricesAndOffers\PricesAndOffersService;
use Zend\Diactoros\ServerRequest;
use PDO;

/**
 * PlaceRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.1
 * @package Chalet
 */
class PlaceRepository extends BaseRepository implements PlaceServiceRepositoryInterface
{
    /**
     * @var PricesAndOffersService
     */
    private $pricesAndOffersService;

    /**
     * {@InheritDoc}
     */
    public function findByLocaleSeoName($seoName, $locale)
    {
        $field = $this->getLocaleField('seoName', $locale);
        $qb    = $this->createQueryBuilder('p');
        $expr  = $qb->expr();

        $qb->select('p, partial c.{id, name, englishName, germanName, countryCode}, partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}')
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
           ->andWhere($expr->gt('FIND_IN_SET(:website, p.websites)', 0))
           ->setMaxResults($limit)
           ->setParameters([

               'showOnHomepage' => true,
               'region'         => $region,
               'website'        => $this->getWebsite(),
           ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param integer $limit
     *
     * @return array
     */
    public function getTypes($placeId, $limit)
    {
        $db = $this->getEntityManager()->getConnection();

        $statement = $db->prepare("SELECT COUNT(a.accommodatie_id) AS total
                                   FROM   accommodatie a
                                   WHERE  a.plaats_id = :place
                                   AND    a.tonen     = 1
                                   AND    a.wzt       = :season
                                   AND    FIND_IN_SET(:website, a.websites) > 0
                                   AND    a.weekendski = :weekendski");

        $statement->bindValue('place', $placeId);
        $statement->bindValue('season', $this->getSeason());
        $statement->bindValue('website', $this->getWebsite());
        $statement->bindValue('weekendski', 0);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] < $limit) {
            return false;
        }

        $locale      = $this->getLocale();
        $localeField = function($field) use ($locale) {
            return ($field . ($locale === 'nl' ? '' : ('_' . $locale)));
        };

        $query = "SELECT t.type_id, a.accommodatie_id AS accommodation_id, r.skigebied_id AS region_id, p.plaats_id AS place_id, c.land_id AS country_id,
                         c.begincode AS country_code, t.kwaliteit AS type_quality, a.kwaliteit AS accommodation_quality, t.optimaalaantalpersonen AS optimal_persons,
                         t.maxaantalpersonen AS max_persons, IF(a.toonper = 1, 'arrangement', 'accommodation') AS type, t.slaapkamers AS bedrooms, t.badkamers AS bathrooms,
                         a.toonper AS accommodation_show, t.kenmerken AS type_features, a.kenmerken AS accommodation_features, p.kenmerken AS place_features,
                         {$localeField('t.naam')} AS type_name, a.naam AS accommodation_name, {$localeField('r.naam')} AS region_name, {$localeField('p.naam')} AS place_name, {$localeField('c.naam')} AS country_name,
                         a.soortaccommodatie AS accommodation_kind, a.zoekvolgorde AS accommodation_search_order, t.zoekvolgorde AS type_search_order, s.zoekvolgorde AS supplier_search_order
                  FROM   type t, accommodatie a, skigebied r, plaats p, land c, leverancier s
                  WHERE  t.accommodatie_id  = a.accommodatie_id
                  AND    s.leverancier_id   = t.leverancier_id
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
                  AND    p.plaats_id        = :place
                  AND    a.tonen            = 1
                  AND    t.tonen            = 1";

        $statement = $db->prepare($query);

        $statement->bindValue('weekendski', 0);
        $statement->bindValue('website', $this->getWebsite());
        $statement->bindValue('season', $this->getSeason());
        $statement->bindValue('place', $placeId);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sorted  = [];
        $done    = [];
        $sorter  = new Sorter($this->getConfig());
        $prices  = $this->getPricesAndOffersService()->getPrices(array_column($results, 'type_id'), new Params(new ServerRequest()));

        foreach ($results as $result) {

            if (in_array($result['accommodation_id'], $done)) {
                continue;
            }

            $done[] = $result['accommodation_id'];

            $result['price'] = (isset($prices[$result['type_id']]) ? $prices[$result['type_id']] : 0);

            $key = $sorter->generateTypeKey($result);
            $sorted[$key] = $result;
        }

        $results = array_slice(array_values($sorted), 0, 3);

        return TypeService::normalizeResults($results);
    }

    /**
     * @param PricesAndOffersService $pricesAndOffersService
     */
    public function setPricesAndOffersService(PricesAndOffersService $pricesAndOffersService)
    {
        $this->pricesAndOffersService = $pricesAndOffersService;
    }

    /**
     * @return PricesAndOffersService
     */
    public function getPricesAndOffersService()
    {
        return $this->pricesAndOffersService;
    }
}
