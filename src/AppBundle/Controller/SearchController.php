<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
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
        $be       = $request->query->get('be', null);
        $ba       = $request->query->get('ba', null);
        $page     = intval($request->query->get('p'));
        $page     = ($page === 0 ? $page : ($page - 1));
        $per_page = intval($this->container->getParameter('app')['results_per_page']);
        $offset   = round($per_page * $page);
        $filters  = $request->query->get('f', []);

        array_walk_recursive($filters, function(&$v) {
            $v = intval($v);
        });

        $formFilters   = [];
        $searchService = $this->get('app.api.search');
        $paginator     = $searchService->build()
                                       ->limit($per_page)
                                       ->offset($offset)
                                       ->sort(SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER, SearchBuilder::SORT_ORDER_ASC)
                                       ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0)
                                       ->filter($filters);

        if ($request->query->has('a')) {
            $paginator->where(SearchBuilder::WHERE_ACCOMMODATION, $a);
        }

        if ($request->query->has('c')) {
            $paginator->where(SearchBuilder::WHERE_COUNTRY, $c);
        }

        if ($request->query->has('r')) {
            $paginator->where(SearchBuilder::WHERE_REGION, $r);
        }

        if ($request->query->has('pl')) {
            $paginator->where(SearchBuilder::WHERE_PLACE, $pl);
        }

        if ($request->query->has('be')) {

            $formFilters['bedrooms'] = $be;
            $paginator->where(SearchBuilder::WHERE_BEDROOMS, $be);
        }

        if ($request->query->has('ba')) {

            $formFilters['bathrooms'] = $ba;
            $paginator->where(SearchBuilder::WHERE_BATHROOMS, $ba);
        }

        $results    = $paginator->results();
        $javascript = $this->get('app.javascript');

        $javascript->set('app.filters.normal',                $filters);
        $javascript->set('app.filters.custom.countries',      $c);
        $javascript->set('app.filters.custom.regions',        $r);
        $javascript->set('app.filters.custom.places',         $pl);
        $javascript->set('app.filters.custom.accommodations', $a);
        $javascript->set('app.filters.form.bedrooms',         $be);
        $javascript->set('app.filters.form.bathrooms',        $ba);

        $data = [

            'paginator'      => $results,
            'filters'        => $filters,
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_service' => $this->container->get('app.filter'),
            'custom_filters' => ['countries' => [], 'regions' => [], 'places' => [], 'accommodations' => []],
            'form_filters'   => $formFilters,
            'prices'         => [],
            'offers'         => [],
        ];

        $typeIds = [];
        foreach ($results as $result) {

            $types = $result->getTypes();
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

        $custom_filter_entities = $searchService->findOnlyNames($c, $r, $pl, $a);
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
        }

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