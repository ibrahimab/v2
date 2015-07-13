<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
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
        $c        = $request->query->get('c',  []);
        $r        = $request->query->get('r',  []);
        $pl       = $request->query->get('pl', []);
        $a        = $request->query->get('a',  []);
        $t        = $request->query->get('t',  []);
        $be       = $request->query->get('be', null);
        $ba       = $request->query->get('ba', null);
        $page     = intval($request->query->get('p'));
        $page     = ($page === 0 ? $page : ($page - 1));
        $per_page = intval($this->container->getParameter('app')['results_per_page']);
        $filters  = $request->query->get('f', []);

        array_walk_recursive($filters, function(&$v) {
            $v = intval($v);
        });

        $formFilters   = [];
        $searchService = $this->get('app.api.search');
        $searchBuilder = $searchService->build()
                                       ->limit($per_page)
                                       ->page($page)
                                       ->sort(SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER, SearchBuilder::SORT_ORDER_ASC)
                                       ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0)
                                       ->filter($filters);
        
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

        $paginator  = $searchBuilder->search();
        $javascript = $this->get('app.javascript');

        $javascript->set('app.filters.normal',                $filters);
        $javascript->set('app.filters.custom.countries',      $c);
        $javascript->set('app.filters.custom.regions',        $r);
        $javascript->set('app.filters.custom.places',         $pl);
        $javascript->set('app.filters.custom.accommodations', $a);
        $javascript->set('app.filters.form.bedrooms',         $be);
        $javascript->set('app.filters.form.bathrooms',        $ba);

        $data = [

            'paginator'      => $paginator,
            'filters'        => $filters,
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_service' => $this->container->get('app.filter'),
            'custom_filters' => ['countries' => [], 'regions' => [], 'places' => [], 'accommodations' => [], 'types' => []],
            'form_filters'   => $formFilters,
            'prices'         => [],
            'offers'         => [],
            'destination'    => $destination,
        ];

        $typeIds = [];
        foreach ($paginator as $accommodation) {

            $types = $accommodation->getTypes();
            foreach ($types as $type) {                
                $typeIds[] = $type->getId();
            }
        }

        if (count($typeIds) > 0) {

            $pricesService  = $this->get('old.prices.wrapper');
            $data['prices'] = $pricesService->get($typeIds);

            $priceService   = $this->get('app.api.price');
            $data['offers'] = $priceService->offers($typeIds);
        }

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
        // $facets = $searchService->facets($paginator, [
        //
        //     FilterService::FILTER_DISTANCE => [FilterService::FILTER_DISTANCE_BY_SLOPE, FilterService::FILTER_DISTANCE_MAX_250, FilterService::FILTER_DISTANCE_MAX_500, FilterService::FILTER_DISTANCE_MAX_1000],
        //     FilterService::FILTER_LENGTH   => [FilterService::FILTER_LENGTH_MAX_100, FilterService::FILTER_LENGTH_MIN_100, FilterService::FILTER_LENGTH_MIN_200, FilterService::FILTER_LENGTH_MIN_400],
        //     FilterService::FILTER_FACILITY => [FilterService::FILTER_FACILITY_CATERING, FilterService::FILTER_FACILITY_INTERNET_WIFI, FilterService::FILTER_FACILITY_SWIMMING_POOL, FilterService::FILTER_FACILITY_SAUNA, FilterService::FILTER_FACILITY_PRIVATE_SAUNA, FilterService::FILTER_FACILITY_PETS_ALLOWED, FilterService::FILTER_FACILITY_FIREPLACE],
        //     FilterService::FILTER_THEME    => [FilterService::FILTER_THEME_KIDS, FilterService::FILTER_THEME_CHARMING_PLACES, FilterService::FILTER_THEME_10_FOR_APRES_SKI, FilterService::FILTER_THEME_SUPER_SKI_STATIONS, FilterService::FILTER_THEME_WINTER_WELLNESS],
        // ]);

        return $this->render('search/' . ($request->isXmlHttpRequest() ? 'results' : 'search') . '.html.twig', $data);
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
}