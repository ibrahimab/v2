<?php
namespace AppBundle\Entity\Search;
use       AppBundle\Entity\BaseRepositoryTrait;
use       AppBundle\Service\Api\Search\SearchServiceRepositoryInterface;
use       AppBundle\Service\Api\Search\SearchService;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\FilterService;
use       AppBundle\Service\Paginator\PaginatorService;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\QueryBuilder;

/**
 * Search repository
 *
 * This is the search repository
 *
 * @author  Ibrahim Abdullah
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.2
 */
class SearchRepository implements SearchServiceRepositoryInterface
{
    use BaseRepositoryTrait;
    /**
     * @const string
     */
    const SORT_BY_DEFAULT    = SearchBuilder::SORT_BY_ACCOMMODATION_NAME;

    /**
     * @const string
     */
    const SORT_ORDER_DEFAULT = SearchBuilder::SORT_ORDER_ASC;

    /**
     * @param array
     */
    private $maximumPersonsMap;

    /**
     * Constructor, injecting the EntityManager
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $maximumPersonsMap
     */
    public function setMaximumPersonsMap($app)
    {
        $this->maximumPersonsMap = $app['maximum_persons_map'];
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findOnlyNames($countries, $regions, $places, $accommodations, $types)
    {
        if (count(array_merge($countries, $regions, $places, $accommodations, $types)) === 0) {
            return [];
        }

        $qb       = $this->getEntityManager()->createQueryBuilder();
        $expr     = $qb->expr();

        $where    = $expr->andX();
        $select   = [];
        $entities = [];

        if (count($countries) > 0) {

            $select[]   = 'partial c.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, startCode}';
            $entities[] = 'AppBundle:Country\Country c';

            $where->add($expr->in('c.' . $this->getLocaleField('name'), ':country_names'));
            $qb->setParameter('country_names', $countries);
        }

        if (count($regions) > 0) {

            $select[]   = 'partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}';
            $entities[] = 'AppBundle:Region\Region r';

            $where->add($expr->in('r.' . $this->getLocaleField('name'), ':region_names'));
            $qb->setParameter('region_names', $regions);
        }

        if (count($places) > 0) {

            $select[]   = 'partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}';
            $entities[] = 'AppBundle:Place\Place p';

            $where->add($expr->in('p.' . $this->getLocaleField('name'), ':place_names'));
            $qb->setParameter('place_names', $places);
        }

        if (count($accommodations) > 0) {

            $select[]   = 'partial a.{id, name, shortDescription, englishShortDescription, germanShortDescription}';
            $entities[] = 'AppBundle:Accommodation\Accommodation a';

            $where->add($expr->in('a.id', ':accommodation_ids'));
            $qb->setParameter('accommodation_ids', $accommodations);
        }

        if (count($types) > 0) {

            $select[]   = 'partial a.{id, name}, partial t.{id, name, englishName, germanName}';
            $entities[] = 'AppBundle:Type\Type t';
            $entities[] = 'AppBundle:Accommodation\Accommodation a';

            $where->add($expr->in('t.id', ':type_ids'));
            $where->add('t.accommodationId = a.id');
            $qb->setParameter('type_ids', $types);
        }

        $qb->select(implode(', ', $select))
           ->add('from', implode(', ', $entities))
           ->where($where);

        return $qb->getQuery()->getResult();
    }

    /**
     * Search
     *
     * @param SearchBuilder $searchBuilder
     * @return array
     */
    public function search(SearchBuilder $searchBuilder)
    {
        $limit = $searchBuilder->block(SearchBuilder::BLOCK_LIMIT);
        $page  = $searchBuilder->block(SearchBuilder::BLOCK_PAGE);
        $qb    = $this->query($searchBuilder)
                      ->add('select', 't,
                                       partial a.{id, name, shortDescription, englishShortDescription, germanShortDescription, features, quality, distanceSlope},
                                       partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, features},
                                       partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, totalSlopesDistance},
                                       partial c.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, startCode}');

        $paginator = new PaginatorService($qb);
        $paginator->setLimit($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @param SearchBuilder $searchBuilder
     * @return integer
     */
    public function count(SearchBuilder $searchBuilder)
    {
        $qb = $this->query($searchBuilder)
                   ->add('select', 'COUNT(t)');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param SearchBuilder $searchBuilder
     * @return QueryBuilder
     */
    public function query(SearchBuilder $searchBuilder)
    {
        $sort_by     = $searchBuilder->block(SearchBuilder::BLOCK_SORT_BY, self::SORT_BY_DEFAULT);
        $sort_order  = $searchBuilder->block(SearchBuilder::BLOCK_SORT_ORDER, self::SORT_ORDER_DEFAULT);
        $filters     = $searchBuilder->block(SearchBuilder::BLOCK_FILTER);
        $where       = $searchBuilder->block(SearchBuilder::BLOCK_WHERE);

        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb->add('from', 'AppBundle:Accommodation\Accommodation a')
           ->leftJoin('a.types', 't')
           ->leftJoin('t.supplier', 's')
           ->leftJoin('a.place',  'p')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->eq('a.display', ':display'))
           ->andWhere($expr->eq('a.displaySearch', ':displaySearch'))
           ->andWhere($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('t.displaySearch', ':displaySearch'))
           ->setParameters([

               'display'       => true,
               'displaySearch' => true,
               'website'       => $this->getWebsite(),
           ]);

        // apply filters
        $this->filters($qb, $filters);

        if (null !== ($sort_field = $this->sortField($sort_by))) {
            $qb->orderBy($sort_field, $sort_order);
        }

        $this->where($where, $qb);

        return $qb;
    }

    /**
     * @param integer $sort
     * @return string
     */
    public function sortField($sort)
    {
        switch ($sort) {

            case SearchBuilder::SORT_BY_ACCOMMODATION_NAME:

                $field = 'a.name';
                break;

            case SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER:

                $field = 't.searchOrder';
                break;

            default:
                $field = null;
        }

        return $field;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Getting type repository
     *
     * @return BaseRepository
     */
    public function getRepository($entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    public function filters($qb, $filters)
    {
        if (null !== $filters) {

            $this->distance($qb, $filters);
            $this->length($qb, $filters);
            $this->facilities($qb, $filters);
            $this->themes($qb, $filters);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     * @return QueryBuilder
     */
    public function distance($qb, $filters)
    {
        if (true === $filters->has(FilterService::FILTER_DISTANCE)) {

            $expr = $qb->expr();
            switch ($filters->filter(FilterService::FILTER_DISTANCE)) {

                case FilterService::FILTER_DISTANCE_BY_SLOPE:

                    $distance = $expr->lte('a.distanceSlope', FilterBuilder::VALUE_DISTANCE_BY_SLOPE);
                    break;

                case FilterService::FILTER_DISTANCE_MAX_250:

                    $distance = $expr->lte('a.distanceSlope', 250);
                    break;

                case FilterService::FILTER_DISTANCE_MAX_500:

                    $distance = $expr->lte('a.distanceSlope', 500);
                    break;

                case FilterService::FILTER_DISTANCE_MAX_1000:

                    $distance = $expr->lte('a.distanceSlope', 1000);
                    break;

                default:
                    return $qb;
            }

            $qb->andWhere($expr->andX($expr->isNotNull('a.distanceSlope'), $distance));
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     * @return QueryBuilder
     */
    public function length($qb, $filters)
    {
        if (true === $filters->has(FilterService::FILTER_LENGTH)) {

            $expr = $qb->expr();
            switch ($filters->filter(FilterService::FILTER_LENGTH)) {

                case FilterService::FILTER_LENGTH_MAX_100:

                    $length = $expr->lt('r.totalSlopesDistance', 100);
                    break;

                case FilterService::FILTER_LENGTH_MIN_100:

                    $length = $expr->gte('r.totalSlopesDistance', 100);
                    break;

                case FilterService::FILTER_LENGTH_MIN_200:

                    $length = $expr->gte('r.totalSlopesDistance', 200);
                    break;

                case FilterService::FILTER_LENGTH_MIN_400:

                    $length = $expr->gte('r.totalSlopesDistance', 400);
                    break;

                default:
                    return $qb;
            }

            $qb->andWhere($expr->andX($expr->isNotNull('r.totalSlopesDistance'), $length));
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     * @return QueryBuilder
     */
    public function facilities($qb, $filters)
    {
        if (true === $filters->has(FilterService::FILTER_FACILITY)) {

            $expr       = $qb->expr();
            $selectors  = [];
            $facilities = $filters->filter(FilterService::FILTER_FACILITY);

            foreach ($facilities as $facility) {

                if (null !== ($selector = $this->facility($qb, $facility))) {
                    $selectors[] = $selector;
                }
            }

            $and = $expr->andX();
            foreach ($selectors as $selector) {
                $and->add($selector);
            }

            $qb->andWhere($and);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param integer $facility
     * @return QueryBuilder
     */
    public function facility($qb, $facility)
    {
        $expr = $qb->expr();
        switch (intval($facility)) {

            case FilterService::FILTER_FACILITY_CATERING:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 1)
                   ->setParameter('accommodation_features_' . $facility, 1);

                break;

            case FilterService::FILTER_FACILITY_INTERNET_WIFI:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_1_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:type_features_2_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_1_' . $facility . ', a.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_2_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_1_' . $facility, 20)
                   ->setParameter('type_features_2_' . $facility, 22)
                   ->setParameter('accommodation_features_1_' . $facility, 21)
                   ->setParameter('accommodation_features_2_' . $facility, 23);

                break;

            case FilterService::FILTER_FACILITY_SWIMMING_POOL:

                $selector = $expr->orX()->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                        ->add($expr->gt('FIND_IN_SET(:accommodation_features_1_' . $facility . ', a.features)', 0))
                                        ->add($expr->gt('FIND_IN_SET(:accommodation_features_2_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 4)
                   ->setParameter('accommodation_features_1_' . $facility, 4)
                   ->setParameter('accommodation_features_2_' . $facility, 11);

                break;

            case FilterService::FILTER_FACILITY_SAUNA:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_1_' . $facility . ', a.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_2_' . $facility , ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 3)
                   ->setParameter('accommodation_features_1_' . $facility, 3)
                   ->setParameter('accommodation_features_2_' . $facility, 10);

                break;

            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 3)
                   ->setParameter('accommodation_features_' . $facility, 3);

                break;

            case FilterService::FILTER_FACILITY_PETS_ALLOWED:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 11)
                   ->setParameter('accommodation_features_' . $facility, 13);

                break;

            case FilterService::FILTER_FACILITY_FIREPLACE:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_' . $facility . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_' . $facility . ', a.features)', 0));

                $qb->setParameter('type_features_' . $facility, 10)
                   ->setParameter('accommodation_features_' . $facility, 12);

                break;

            default:
                $selector = null;
        }

        return $selector;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     * @return QueryBuilder
     */
    public function themes($qb, $filters)
    {
        if (true === $filters->has(FilterService::FILTER_THEME)) {

            $expr       = $qb->expr();
            $selectors  = [];
            $themes     = $filters->filter(FilterService::FILTER_THEME);

            foreach ($themes as $theme) {

                if (null !== ($selector = $this->theme($qb, $theme))) {
                    $selectors[] = $selector;
                }
            }

            $and = $expr->andX();
            foreach ($selectors as $selector) {
                $and->add($selector);
            }

            $qb->andWhere($and);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param integer $theme
     * @return QueryBuilder
     */
    public function theme($qb, $theme)
    {
        $expr     = $qb->expr();
        $selector = $expr->gt('FIND_IN_SET(:place_features_theme_' . $theme . ', p.features)', 0);

        switch ($theme) {

            case FilterService::FILTER_THEME_KIDS:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_theme_' . $theme . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_theme_' . $theme . ', a.features)', 0));

                $qb->setParameter(':type_features_theme_' . $theme, 5)
                   ->setParameter(':accommodation_features_theme_' . $theme, 5);

                break;

            case FilterService::FILTER_THEME_CHARMING_PLACES:

                $qb->setParameter(':place_features_theme_' . $theme, 13);
                break;

            case FilterService::FILTER_THEME_WINTER_WELLNESS:

                $selector = $expr->orX()
                                 ->add($expr->gt('FIND_IN_SET(:type_features_theme_' . $theme . ', t.features)', 0))
                                 ->add($expr->gt('FIND_IN_SET(:accommodation_features_theme_' . $theme . ', a.features)', 0));

                $qb->setParameter(':type_features_theme_' . $theme, 9);
                $qb->setParameter(':accommodation_features_theme_' . $theme, 9);

                break;

            case FilterService::FILTER_THEME_SUPER_SKI_STATIONS:

                $qb->setParameter(':place_features_theme_' . $theme, 14);
                break;

            case FilterService::FILTER_THEME_10_FOR_APRES_SKI:

                $qb->setParameter(':place_features_theme_' . $theme, 6);
                break;

            default:
            return null;
        }

        return $selector;
    }

    /**
     * @param array $where
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    public function where($where, $qb)
    {
        $expr = $qb->expr();

        foreach ($where as $clause) {

            switch ($clause['field']) {

                case SearchBuilder::WHERE_WEEKEND_SKI:

                    $qb->andWhere($expr->eq('a.weekendSki', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_ACCOMMODATION:

                    $qb->andWhere($expr->in('a.id', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_TYPE:

                    $qb->andWhere($expr->in('t.id', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_COUNTRY:

                    $qb->andWhere($expr->in('c.' . $this->getLocaleField('name'), ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_REGION:

                    $qb->andWhere($expr->in('r.' . $this->getLocaleField('name'), ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_PLACE:

                    $qb->andWhere($expr->in('p.' . $this->getLocaleField('name'), ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_BEDROOMS:

                    $qb->andWhere($expr->gte('t.bedrooms', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_BATHROOMS:

                    $qb->andWhere($expr->gte('t.bathrooms', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;

                case SearchBuilder::WHERE_PERSONS:

                    if ($clause['value'] > 0) {

                        if ($clause['value'] >= 40) {

                            $min = $clause['value'];
                            $max = 1000;

                        } else if ($clause['value'] > 20) {

                            $min = $clause['value'];
                            $max = 50;

                        } else {

                            $min = $clause['value'];
                            $max = (isset($this->maximumPersonsMap[$clause['value']]) ? $this->maximumPersonsMap[$clause['value']] : $clause['value']);
                        }
                    }

                    $qb->andWhere($expr->gte('t.optimalResidents', ':minResidents'));
                    $qb->andWhere($expr->lte('t.maxResidents', ':maxResidents'));

                    $qb->setParameter('minResidents', $min);
                    $qb->setParameter('maxResidents', $max);

                    if( WebsiteConcern::WEBSITE_ZOMERHUISJE_NL != $this->getWebsite() ) {
                        // max 1 bedroom per person (not for Zomerhuisje.nl)
                        $qb->andWhere($expr->lte('t.bedrooms', ':bedroomsBasedOnResidents'));
                        $qb->setParameter('bedroomsBasedOnResidents', $min);
                    }

                    break;

                case SearchBuilder::WHERE_TYPES:

                    $qb->andWhere($expr->in('t.id', ':where_' . $clause['field']));
                    $qb->setParameter('where_' . $clause['field'], $clause['value']);

                    break;
            }
        }

        return $qb;
    }
}