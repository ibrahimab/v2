<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\Api\Search\Result\Sorter;
use       AppBundle\Service\FilterService;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\JsonResponse;
use       Symfony\Component\HttpFoundation\Response;

/**
 * SearchController
 *
 * This controller is the new search component for Chalet
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="search", title="search-book", translate=true, active=true, path="search")
 */
class SearchController extends Controller
{
    /**
     * @Route(path="/zoek-en-boek.php", name="search_nl", options={"expose": true})
     * @Route(path="/search-and-book.php",  name="search_en", options={"expose": true})
     */
    public function index(Request $request)
    {
        $filterService = $this->container->get('app.filter');
        $locale        = $request->getLocale();
        $reroute       = $this->reroute($filterService, $request->query->all());
        
        if (count($reroute) > 0) {
            return $this->redirect($this->generateUrl('search_' . $locale, $reroute), 301);
        }
        
        $start    = microtime(true);
        $c        = $request->query->get('c',  []);
        $r        = $request->query->get('r',  []);
        $pl       = $request->query->get('pl', []);
        $a        = $request->query->get('a',  []);
        $t        = $request->query->get('t',  []);
        $be       = $request->query->get('be', null);
        $ba       = $request->query->get('ba', null);
        $w        = $request->query->get('w',  null);
        $pe       = $request->query->get('pe', null);
        $s        = $request->query->get('s',  Sorter::SORT_NORMAL);
        $page     = intval($request->query->get('p'));
        $page     = ($page === 0 ? $page : ($page - 1));
        $per_page = intval($this->container->getParameter('app')['results_per_page']);
        $filters  = $request->query->get('f', []);

        array_walk_recursive($filters, function(&$v) {
            $v = intval($v);
        });

        $offers      = [];
        $prices      = [];
        $formFilters = [];
        $typeIds     = [];
        
        $surveyService          = $this->get('app.api.booking.survey');
        $seasonService          = $this->get('app.api.season');
        $searchService          = $this->get('app.api.search');
        $priceService           = $this->get('app.api.price');
        $generalSettingsService = $this->get('app.api.general.settings');
        
        $searchBuilder = $searchService->build()
                                       ->limit($per_page)
                                       ->page($page)
                                       ->sort(SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER, SearchBuilder::SORT_ORDER_ASC)
                                       ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0)
                                       ->filter($filters);
        
        if ($request->query->has('w') && !$request->query->has('pe')) {
            
            /**
             * weekend is known
             * persons is not known
             *
             * get available types for selected weekend
             * and set offers and type ids
             */
            $priceService->setWeekend($request->query->get('w'));
            $priceService->getDataWithWeekendAndOrPersons();
            
            $searchBuilder->where(SearchBuilder::WHERE_TYPES, $priceService->getTypes());
            $formFilters['weekend'] = $request->query->get('w');
        }

        if ($request->query->has('w') && $request->query->has('pe')) {

            /**
             * weekend is known
             * persons is known
             *
             * get prices and offers with weekend and persons
             */
            $priceService->setWeekend($request->query->get('w'));
            $priceService->setPersons($request->query->get('pe'));
            $priceService->getDataWithWeekendAndOrPersons();
            
            $searchBuilder->where(SearchBuilder::WHERE_TYPES, $priceService->getTypes());
            $formFilters['weekend'] = $w;
            $formFilters['persons'] = $pe;
        }

        $destination = false;

        if ($request->query->has('a')) {

            $searchBuilder->where(SearchBuilder::WHERE_ACCOMMODATION, $a);
            $destination = true;
        }

        if ($request->query->has('t')) {

            $searchBuilder->where(SearchBuilder::WHERE_TYPE, $t);
            $destination = true;
        }

        if ($request->query->has('c')) {

            $searchBuilder->where(SearchBuilder::WHERE_COUNTRY, $c);
            $destination = true;
        }

        if ($request->query->has('r')) {

            $searchBuilder->where(SearchBuilder::WHERE_REGION, $r);
            $destination = true;
        }

        if ($request->query->has('pl')) {

            $searchBuilder->where(SearchBuilder::WHERE_PLACE, $pl);
            $destination = true;
        }

        if ($request->query->has('be')) {

            $formFilters['bedrooms'] = $be;
            $searchBuilder->where(SearchBuilder::WHERE_BEDROOMS, $be);
        }

        if ($request->query->has('ba')) {

            $formFilters['bathrooms'] = $ba;
            $searchBuilder->where(SearchBuilder::WHERE_BATHROOMS, $ba);
        }

        if ($request->query->has('pe')) {

            $formFilters['persons'] = $pe;
            $searchBuilder->where(SearchBuilder::WHERE_PERSONS, $pe);
        }

        $resultset  = $searchBuilder->search();
        $javascript = $this->get('app.javascript');

        $javascript->set('app.filters.normal',                $filters);
        $javascript->set('app.filters.custom.countries',      $c);
        $javascript->set('app.filters.custom.regions',        $r);
        $javascript->set('app.filters.custom.places',         $pl);
        $javascript->set('app.filters.custom.accommodations', $a);
        $javascript->set('app.filters.form.bedrooms',         $be);
        $javascript->set('app.filters.form.bathrooms',        $ba);
        $javascript->set('app.filters.form.sort',             $s);

        $seasons = $seasonService->seasons();
        $data    = [

            'season'         => (isset($seasons[0]) ? $seasons[0]['id'] : 0),
            'resultset'      => $resultset,
            'filters'        => $filters,
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_service' => $filterService,
            'custom_filters' => ['countries' => [], 'regions' => [], 'places' => [], 'accommodations' => [], 'types' => []],
            'form_filters'   => $formFilters,
            'destination'    => $destination,
            'weekends'       => $seasonService->weekends($seasons),
            'surveys'        => [],
            'sort'           => $s,
        ];
        
        $typeIds = $resultset->allTypeIds();
        $priceService = $this->get('app.api.price');

        if (!$request->query->has('w') && !$request->query->has('pe')) {

            /**
             * no weekend
             * no persons
             */
            $priceService->setTypes($typeIds);
            $priceService->getDataWithWeekendAndOrPersons();
        }

        $surveyData = $surveyService->statsByTypes($typeIds);
        $surveys    = [];

        foreach ($surveyData as $survey) {
            $surveys[$survey['typeId']] = $survey;
        }

        $resultset->setPrices($priceService->getPrices());
        $resultset->setOffers($priceService->getOffers());
        $resultset->setSurveys($surveys);
        $resultset->sorter()->setOrderBy($s);
        $resultset->setMetadata();
        $resultset->setResale($this->get('app.concern.website')->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE));
        $resultset->sorter()->sort();

        $custom_filter_entities = $searchService->findOnlyNames($c, $r, $pl, $a, $t);
        foreach ($custom_filter_entities as $entity) {

            if ($entity instanceof CountryServiceEntityInterface) {
                $data['custom_filters']['countries'][$entity->getId()] = $entity;
            }

            if ($entity instanceof RegionServiceEntityInterface) {
                $data['custom_filters']['regions'][$entity->getId()] = $entity;
            }

            if ($entity instanceof PlaceServiceEntityInterface) {
                $data['custom_filters']['places'][$entity->getId()] = $entity;
            }

            if ($entity instanceof AccommodationServiceEntityInterface) {
                $data['custom_filters']['accommodations'][$entity->getId()] = $entity;
            }

            if ($entity instanceof TypeServiceEntityInterface) {
                $data['custom_filters']['types'][$entity->getId()] = $entity;
            }
        }

        $facetFilters = [

            FilterService::FILTER_DISTANCE => [FilterService::FILTER_DISTANCE_BY_SLOPE, FilterService::FILTER_DISTANCE_MAX_250, FilterService::FILTER_DISTANCE_MAX_500, FilterService::FILTER_DISTANCE_MAX_1000],
            FilterService::FILTER_LENGTH   => [FilterService::FILTER_LENGTH_MAX_100, FilterService::FILTER_LENGTH_MIN_100, FilterService::FILTER_LENGTH_MIN_200, FilterService::FILTER_LENGTH_MIN_400],
            FilterService::FILTER_FACILITY => [FilterService::FILTER_FACILITY_CATERING, FilterService::FILTER_FACILITY_INTERNET_WIFI, FilterService::FILTER_FACILITY_SWIMMING_POOL, FilterService::FILTER_FACILITY_SAUNA, FilterService::FILTER_FACILITY_PRIVATE_SAUNA, FilterService::FILTER_FACILITY_PETS_ALLOWED, FilterService::FILTER_FACILITY_FIREPLACE],
            FilterService::FILTER_THEME    => [FilterService::FILTER_THEME_KIDS, FilterService::FILTER_THEME_CHARMING_PLACES, FilterService::FILTER_THEME_10_FOR_APRES_SKI, FilterService::FILTER_THEME_SUPER_SKI_STATIONS, FilterService::FILTER_THEME_WINTER_WELLNESS],
        ];

        foreach ($filters as $filter => $activeFilterValues) {

            if (false === FilterService::multiple($filter)) {

                $facetFilters[$filter] = array_filter($facetFilters[$filter], function($value) use ($activeFilterValues) {
                    return $value === $activeFilterValues;
                });
            }
        }

        $data['facet_service'] = $searchService->facets($resultset, $facetFilters);
        $data['search_time']   = round((microtime(true) - $start), 2);
        $data['searchFormMessageSearchWithoutDates']   = $generalSettingsService->getSearchFormMessageSearchWithoutDates();

        return $this->render('search/' . ($request->isXmlHttpRequest() ? 'results' : 'search') . '.html.twig', $data);
    }

    /**
     * @Route(path="/search/count", name="search_count", options={"expose": true})
     */
    public function count(Request $request)
    {
        $searchService = $this->get('app.api.search');
        $searchBuilder = $searchService->build()
                                       ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0);

        if ($request->query->has('a')) {
            $searchBuilder->where(SearchBuilder::WHERE_ACCOMMODATION, $request->query->get('a'));
        }

        if ($request->query->has('t')) {
            $searchBuilder->where(SearchBuilder::WHERE_TYPE, $request->query->get('t'));
        }

        if ($request->query->has('c')) {
            $searchBuilder->where(SearchBuilder::WHERE_COUNTRY, $request->query->get('c'));
        }

        if ($request->query->has('r')) {
            $searchBuilder->where(SearchBuilder::WHERE_REGION, $request->query->get('r'));
        }

        if ($request->query->has('pl')) {
            $searchBuilder->where(SearchBuilder::WHERE_PLACE, $request->query->get('pl'));
        }

        if ($request->query->has('be')) {
            $searchBuilder->where(SearchBuilder::WHERE_BEDROOMS, $request->query->get('be'));
        }

        if ($request->query->has('ba')) {
            $searchBuilder->where(SearchBuilder::WHERE_BATHROOMS, $request->query->get('ba'));
        }

        if ($request->query->has('w') && !$request->query->has('pe')) {

            $priceService = $this->get('app.api.price');
            $types        = $priceService->availableTypes($request->query->get('w'));

            $searchBuilder->where(SearchBuilder::WHERE_TYPES, array_keys($types));
        }

        if ($request->query->has('w') && $request->query->has('pe')) {

            $priceService = $this->get('app.api.price');
            $types        = $priceService->pricesWithWeekendAndPersons($request->query->get('w'), $request->query->get('pe'));

            $searchBuilder->where(SearchBuilder::WHERE_TYPES, array_keys($types));
        }

        return new JsonResponse([
            'count' => $searchBuilder->count(),
        ]);
    }

    /**
     * @Route(path="/search/save", name="save_search", options={"expose": true})
     */
    public function save(Request $request)
    {
        $userService = $this->get('app.api.user');
        $user        = $userService->user();
        $f           = $request->query->get('f', []);
        $be          = $request->query->get('be', null);
        $ba          = $request->query->get('ba', null);
        $c           = $request->query->get('c', []);
        $r           = $request->query->get('r', []);
        $pl          = $request->query->get('pl', []);
        $a           = $request->query->get('a', []);

        array_walk_recursive($f, function(&$v) {
            $v = intval($v);
        });

        if (null !== $user) {
            $userService->saveSearch(['f' => $f, 'be' => $be, 'ba' => $ba, 'c' => $c, 'r' => $r, 'pl' => $pl, 'a' => $a]);
        }

        return new JsonResponse([

            'type'    => 'success',
            'message' => 'Your search was successfully saved',
        ]);
    }
    
    public function reroute(FilterService $filterService, $params)
    {
        $reroute = [];
        
        foreach ($params as $param => $value) {
            
            $matches = ['f' => []];
            $result  = preg_match('/(?P<param>[a-zA-Z_-]+)(?:(?P<value>[0-9]+))?/i', $param, $matches);
            $data    = $matches['param'] === 'vf_kenm' ? $matches['value'] : $value;
            $data    = (int)$data;

            if ($matches['param'] === 'vf_badk') {
                
                $reroute['ba'] = $data;
                
            } else if ($matches['param'] === 'fap') {
                
                $reroute['pe'] = $data;
                
            } else if ($matches['param'] === 'fas') {
                
                $reroute['be'] = $data;
                
            } else if ($matches['param'] === 'fad') {
                
                $reroute['w'] = $data;
                
            } else {
            
                if (null !== ($filter = $filterService->convertThemeFilter($matches['param'], $data))) {
                    
                    if (!isset($reroute['f'])) {
                        $reroute['f'] = [];
                    }
        
                    if (FilterService::multiple($filter['filter'])) {

                        if (!isset($reroute['f'][$filter['filter']])) {
                            $reroute['f'][$filter['filter']] = [];
                        }
                        
                        $reroute['f'][$filter['filter']][] = $filter['value'];
            
                    } else {
                        $reroute['f'][$filter['filter']] = $filter['value'];
                    }
                }
            }
        }
        
        return $reroute;
    }
}