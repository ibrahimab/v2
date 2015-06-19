<?php
namespace AppBundle\Entity\Search;
use       AppBundle\Service\Api\Search\SearchServiceRepositoryInterface;
use       AppBundle\Service\Api\Search\SearchService;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\FilterService;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Search repository
 *
 * This is the search repository
 *
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class SearchRepository implements SearchServiceRepositoryInterface
{
    /**
     * @const string
     */
    const SORT_BY_DEFAULT    = SearchBuilder::SORT_BY_ACCOMMODATION_NAME;
                              
    /**                       
     * @const string          
     */                       
    const SORT_ORDER_DEFAULT = SearchBuilder::SORT_ORDER_ASC;

    /**
     * @var integer
     */
    protected $season;

    /**
     * @var string
     */
    protected $website;

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
     * Search
     *
     * @param SearchBuilder $searchBuilder
     * @return array
     */
    public function search(SearchBuilder $searchBuilder)
    {
        $limit       = $searchBuilder->block(SearchBuilder::BLOCK_LIMIT);
        $offset      = $searchBuilder->block(SearchBuilder::BLOCK_OFFSET);
        $sort_by     = $searchBuilder->block(SearchBuilder::BLOCK_SORT_BY, self::SORT_BY_DEFAULT);
        $sort_order  = $searchBuilder->block(SearchBuilder::BLOCK_SORT_ORDER, self::SORT_ORDER_DEFAULT);
        $filters     = $searchBuilder->block(SearchBuilder::BLOCK_FILTER);
        $where       = $searchBuilder->block(SearchBuilder::BLOCK_WHERE);

        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb->add('select', 't,
                            partial a.{id, name, shortDescription, englishShortDescription, germanShortDescription},
                            partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName},
                            partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName},
                            partial c.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName, startCode}')
           ->add('from', 'AppBundle:Accommodation\Accommodation a')
           ->innerJoin('a.types', 't')
           ->innerJoin('a.place',  'p')
           ->innerJoin('p.region', 'r')
           ->innerJoin('p.country', 'c')
           ->where($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('t.displaySearch', ':displaySearch'))
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->having($expr->gt('SIZE(a.types)', 1))
           ->setParameters([

               'display'       => true,
               'displaySearch' => true,
           ]);

        // apply filters
        $this->filters($qb, $filters);

        if (null !== ($sort_field = $this->sortField($sort_by))) {
            $qb->orderBy($sort_field, $sort_order);
        }

        // $this->where($where, $qb);

        // dump($qb->getQuery()->getSQL());exit;

        $paginator = new Paginator($qb, true);
        $paginator->page = [

            'current' => (round($offset / $limit) + 1),
            'last'    => round(count($paginator) / $limit),
        ];

        if ((int)$paginator->page['last'] === 0) {
            $paginator->page['last'] = 1;
        }

        return $paginator;
    }

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

    /**
     * Setting season
     *
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern)
    {
        $this->season = $seasonConcern->get();
    }

    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Setting website
     *
     * @param WebsiteConcern $websiteConcern
     */
    public function setWebsite(WebsiteConcern $websiteConcern)
    {
        $this->website = $websiteConcern->get();
    }

    /**
     * Getting website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Getting either the option passed in or the default
     *
     * @param array $options
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }

    public function filters($qb, $filters)
    {
        if (null !== $filters) {

            $this->distance($qb, $filters);
            $this->length($qb, $filters);
            $this->facilities($qb, $filters);
            $this->bathroom($qb, $filters);
            $this->themes($qb, $filters);
        }

        return $qb;
    }

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

    public function facility($qb, $facility)
    {
        $expr = $qb->expr();
        switch (intval($facility)) {

            case FilterService::FILTER_FACILITY_CATERING:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 1);
                $qb->setParameter('accommodation_features_' . $facility, 1);

                break;

            case FilterService::FILTER_FACILITY_INTERNET_WIFI:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 20);
                $qb->setParameter('accommodation_features_' . $facility, 22);

                break;

            case FilterService::FILTER_FACILITY_SWIMMING_POOL:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 4);
                $qb->setParameter('accommodation_features_' . $facility, [4, 11]);

                break;

            case FilterService::FILTER_FACILITY_SAUNA:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 3);
                $qb->setParameter('accommodation_features_' . $facility, [3, 10]);

                break;

            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 3);
                $qb->setParameter('accommodation_features_' . $facility, 3);

                break;

            case FilterService::FILTER_FACILITY_PETS_ALLOWED:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 11);
                $qb->setParameter('accommodation_features_' . $facility, 13);

                break;

            case FilterService::FILTER_FACILITY_FIREPLACE:

                $selector = $expr->orX($expr->in('t.features', ':type_features_' . $facility), $expr->in('a.features', ':accommodation_features_' . $facility));

                $qb->setParameter('type_features_' . $facility, 10);
                $qb->setParameter('accommodation_features_' . $facility, 12);

                break;

            default:
                $selector = null;
        }

        return $selector;
    }

    public function bathroom($qb, $filters)
    {
        if (true === $filters->has(FilterService::FILTER_BATHROOM)) {

            $expr = $qb->expr();

            switch (FilterService::FILTER_BATHROOM) {

                case FilterService::FILTER_BATHROOM_MIN_2:
                
                    $bathroom = 2;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_3:
                
                    $bathrooms = 3;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_4:
                
                    $bathrooms = 4;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_5:
                
                    $bathrooms = 5;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_6:
                
                    $bathrooms = 6;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_8:
                
                    $bathrooms = 8;
                    break;

                case FilterService::FILTER_BATHROOM_MIN_10:
                
                    $bathrooms = 10;
                    break;

                default:
                    return $qb;
            }

            $bathrooms = 10;
            $bathroom  = $expr->gte('t.bathrooms', ':bathrooms');

            $qb->setParameter('bathrooms', $bathrooms);
            $qb->andWhere($bathroom);
        }

        return $qb;
    }

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

    public function theme($qb, $theme)
    {
        $expr     = $qb->expr();
        $selector = $expr->in('p.features', ':place_features_theme_' . $theme);

        switch ($theme) {

            case FilterService::FILTER_THEME_KIDS:

                $selector = $expr->orX($expr->in('t.features', ':type_features_theme_' . $theme), $expr->in('a.features', ':accommodation_features_theme_' . $theme));

                $qb->setParameter(':type_features_theme_' . $theme, 5);
                $qb->setParameter(':accommodation_features_theme_' . $theme, 5);

                break;

            case FilterService::FILTER_THEME_CHARMING_PLACES:
            
                $qb->setParameter(':place_features_theme_' . $theme, 13);
                break;

            case FilterService::FILTER_THEME_WINTER_WELLNESS:

                $selector = $expr->orX($expr->in('t.features', ':type_features_theme_' . $theme), $expr->in('a.features', ':accommodation_features_theme_' . $theme));

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

    public function where($where, $qb)
    {
        $expr = $qb->expr();
        foreach ($where as $clause) {

            switch ($clause['field']) {

                case SearchBuilder::WHERE_WEEKEND_SKI:
                    $field = 'a.weekendSki';
                break;
            }

            $qb->andWhere($expr->eq($field, $clause['value']));
        }

        return $qb;
    }
}