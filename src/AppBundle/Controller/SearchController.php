<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\JsonResponse;

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
        $page     = intval($request->query->get('p'));
        $page     = ($page === 0 ? $page : ($page - 1));
        $per_page = intval($this->container->getParameter('app')['results_per_page']);
        $offset   = round($per_page * $page);
        $filters  = $request->query->get('f', []);
        array_walk_recursive($filters, function(&$v) {
            $v = intval($v);
        });

        $searchService = $this->get('app.api.search');
        $paginator     = $searchService->build()
                                       ->limit($per_page)
                                       ->offset($offset)
                                       ->sort(SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER, SearchBuilder::SORT_ORDER_ASC)
                                       ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0)
                                       ->filter($filters);

        if ($request->query->has('a')) {
            $paginator->where(SearchBuilder::WHERE_ACCOMMODATION, $request->query->get('a'));
        }

        if ($request->query->has('c')) {            
            $paginator->where(SearchBuilder::WHERE_COUNTRY, $request->query->get('c'));
        }
        
        if ($request->query->has('r')) {
            $paginator->where(SearchBuilder::WHERE_REGION, $request->query->get('r'));
        }
        
        if ($request->query->has('pl')) {
            $paginator->where(SearchBuilder::WHERE_PLACE, $request->query->get('pl'));
        }
        
        $results    = $paginator->results();
        $javascript = $this->get('app.javascript');

        $javascript->set('app.filters.normal',                $filters);
        $javascript->set('app.filters.custom.countries',      $request->query->get('c',  []));
        $javascript->set('app.filters.custom.regions',        $request->query->get('r',  []));
        $javascript->set('app.filters.custom.places',         $request->query->get('pl', []));
        $javascript->set('app.filters.custom.accommodations', $request->query->get('a',  []));

        $data = [

            'paginator' => $results,
            'filters'   => $filters,
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_service' => $this->container->get('app.filter'),
            'custom_filters' => ['countries' => [], 'regions' => [], 'places' => [], 'accommodations' => []],
        ];
        
        $countries = $searchService->findOnlyNames($request->query->get('c'));
        dump($countries);

        foreach ($it as $result) {
            
            $place = $result->getPlace();
            
            if (null !== $place) {
                
                if ($request->query->has('c') && null !== ($country = $place->getCountry())) {
                    $data['custom_filters']['countries'][$country->getId()] = $country;
                }
                
                if ($request->query->has('r') && null !== ($region = $place->getRegion())) {
                    $data['custom_filters']['regions'][$region->getId()] = $region;
                }
            }
        }
        
        if ($request->query->has('a')) {
            $data['accommodation_filter'] = $results->getIterator()->current();
        }
        
        if ($request->query->has('pl')) {
            $data['place_filter'] = $results->getIterator()->current()->getPlace();
        }
        
        return $this->render('search/' . ($request->isXmlHttpRequest() ? 'results' : 'search') . '.html.twig', $data);
    }

    /**
     * @Route(path="/zoek-en-boek/opslaan", name="save_search_nl", options={"expose": true})
     * @Route(path="/search-and-book/save", name="save_search_en", options={"expose": true})
     */
    public function save(Request $request)
    {
        if (count($search = $request->query->get('f', [])) === 0) {
            return $this->redirectToRoute('search_' . $request->getLocale());
        }

        $userService = $this->get('app.api.user');
        $user        = $userService->user();
        $filters     = $request->query->get('f', []);
        array_walk_recursive($filters, function(&$v) {
            $v = intval($v);
        });

        if (null !== $user) {
            $userService->saveSearch($user, $filters);
        }

        return $this->redirectToRoute('search_' .  $request->getLocale(), ['f' => $filters]);
    }
}