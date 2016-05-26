<?php
namespace AppBundle\Controller;

use AppBundle\Concern\WebsiteConcern;
use AppBundle\Annotation\Breadcrumb;
use AppBundle\Service\Api\Search\FacetService;
use AppBundle\Service\Api\Search\Params;
use AppBundle\Service\Api\Search\Filter\Manager   as FilterManager;
use AppBundle\Service\Api\Search\Filter\Converter as FilterConverter;
use AppBundle\Service\Api\Search\Result\Resultset;
use AppBundle\Service\Api\Search\Result\Sorter;
use AppBundle\Service\Api\Search\Result\PriceText;
use AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory as PsrFactory;

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
 */
class SearchController extends Controller
{
    /**
     * @Route(path="/zoek-en-boek.php", name="search_nl", options={"expose": true})
     * @Route(path="/search-and-book.php",  name="search_en", options={"expose": true})
     * @Breadcrumb(name="search", title="search-book", translate=true, active=true, path="search")
     */
    public function index(Request $request)
    {
        $locale  = $this->get('app.concern.locale')->get();
        $reroute = $this->reroute($request->query->all());

        if (count($reroute) > 0) {
            return $this->redirectToRoute('search_' . $locale, $reroute, 301);
        }

        if (false !== ($saved = $this->hasSavedSearch($request))) {

            $this->get('session')->remove('search');
            return $this->redirectToRoute('search_' . $locale, $saved, 301);
        }

        $searchService = $this->get('app.api.search');
        $params        = $searchService->createParamsFromRequest($request);

        $this->saveToSession($params);

        $start                  = microtime(true);
        $surveyService          = $this->get('app.api.booking.survey');
        $seasonService          = $this->get('app.api.season');
        $supplierService        = $this->get('app.api.supplier');
        $generalSettingsService = $this->get('app.api.general.settings');
        $legacyCmsUserService   = $this->get('app.legacy.cmsuser');

        $resultset              = $searchService->search($params);
        $paginator              = $searchService->paginate($resultset, $params);
        $destination            = $searchService->hasDestination($params);
        $typeIds                = $searchService->extractTypeIds($resultset);
        $filterNames            = $searchService->getFilterNames($params);

        // internal users: allow searching for suppliers
        if ($legacyCmsUserService->shouldShowInternalInfo()) {

            // get all suppliers as an array
            // @todo: is there a single function to do this?
            foreach ($supplierService->all(['order' => ['name' =>'asc'] ]) as $supplier) {
                $suppliers[$supplier->getId()] = $supplier->getName();
            }
        }

        $params->setOfferPage(false);

        $this->setupJavascriptParameters($params);

        $this->get('app.javascript')
             ->set('app.search_route', 'search');

        $seasons  = $seasonService->seasons();
        $seasonId = $seasonService->current()['id'];
        $data     = [

            'offerPage'      => $params->getOfferPage(),
            'offerBox'       => false,
            'season'         => $seasonId,
            'resultset'      => $resultset,
            'paginator'      => $paginator,
            'filters'        => $params->getFilters() ?: [],
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_manager' => new FilterManager(),
            'custom_filters' => [

                'countries'      => $filterNames['countries'],
                'regions'        => $filterNames['regions'],
                'places'         => $filterNames['places'],
                'accommodations' => $filterNames['accommodations'],
                'suppliers'      => $filterNames['suppliers'],
            ],

            'form_filters'   => [

                'weekend'    => $params->getWeekend(),
                'persons'    => $params->getPersons(),
                'bedrooms'   => $params->getBedrooms(),
                'bathrooms'  => $params->getBathrooms(),
                'freesearch' => $params->getFreesearch(),
            ],
            'destination'    => $destination,
            'weekends'       => $seasonService->futureWeekends($seasons),
            'suppliers'      => $suppliers,
            'surveys'        => [],
            'sort'           => $params->getSort(),
            'searchFormMessageSearchWithoutDates' => $generalSettingsService->getSearchFormMessageSearchWithoutDates(),
        ];

        $facetFilters = $this->getFacetFilters($params);

        $data['facet_service'] = $searchService->facets($resultset, $facetFilters);
        $data['search_time']   = round((microtime(true) - $start), 2);
        $data['surveys']       = $searchService->surveys($resultset);
        $data['price_text']    = new PriceText;
        $data['searchFormMessageSearchWithoutDates'] = $generalSettingsService->getSearchFormMessageSearchWithoutDates();

        return $this->render('search/' . ($request->isXmlHttpRequest() ? 'results' : 'search') . '.html.twig', $data);
    }

    /**
     * @Route(path="/aanbiedingen.php", name="search_offers_nl", options={"expose": true})
     * @Breadcrumb(name="search", title="search-book", translate=true, active=true, path="search")
     */
    public function offers(Request $request)
    {
        $locale  = $this->get('app.concern.locale')->get();
        $reroute = $this->reroute($request->query->all());

        if (count($reroute) > 0) {
            return $this->redirectToRoute('search_offers_' . $locale, $reroute, 301);
        }

        if (false !== ($saved = $this->hasSavedSearch($request))) {

            $this->get('session')->remove('search');
            return $this->redirectToRoute('search_offers_' . $locale, $saved, 301);
        }

        $searchService = $this->get('app.api.search');
        $params        = $searchService->createParamsFromRequest($request);

        if (!$request->query->has('o')) {
            $params->setOfferPage(true);
        }

        $this->saveToSession($params);

        $start                  = microtime(true);
        $surveyService          = $this->get('app.api.booking.survey');
        $seasonService          = $this->get('app.api.season');
        $supplierService        = $this->get('app.api.supplier');
        $generalSettingsService = $this->get('app.api.general.settings');
        $legacyCmsUserService   = $this->get('app.legacy.cmsuser');

        $resultset              = $searchService->search($params);
        $paginator              = $searchService->paginate($resultset, $params);
        $destination            = $searchService->hasDestination($params);
        $typeIds                = $searchService->extractTypeIds($resultset);
        $filterNames            = $searchService->getFilterNames($params);

        // internal users: allow searching for suppliers
        if ($legacyCmsUserService->shouldShowInternalInfo()) {

            // get all suppliers as an array
            // @todo: is there a single function to do this?
            foreach ($supplierService->all(['order' => ['name' =>'asc'] ]) as $supplier) {
                $suppliers[$supplier->getId()] = $supplier->getName();
            }

        }

        $this->setupJavascriptParameters($params);

        $this->get('app.javascript')
             ->set('app.search_route', 'search_offers');

        $facetFilters = $this->getFacetFilters($params);
        $seasons      = $seasonService->seasons();
        $seasonId     = $seasonService->current()['id'];
        $data         = [

            'offerPage'      => $params->getOfferPage(),
            'offerBox'       => true,
            'season'         => $seasonId,
            'resultset'      => $resultset,
            'paginator'      => $paginator,
            'filters'        => $params->getFilters() ?: [],
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_manager' => new FilterManager(),
            'custom_filters' => [

                'countries'      => $filterNames['countries'],
                'regions'        => $filterNames['regions'],
                'places'         => $filterNames['places'],
                'accommodations' => $filterNames['accommodations'],
                'suppliers'      => $filterNames['suppliers'],
            ],

            'form_filters'   => [

                'weekend'    => $params->getWeekend(),
                'persons'    => $params->getPersons(),
                'bedrooms'   => $params->getBedrooms(),
                'bathrooms'  => $params->getBathrooms(),
                'freesearch' => $params->getFreesearch(),
            ],
            'destination'    => $destination,
            'weekends'       => $seasonService->futureWeekends($seasons),
            'suppliers'      => $suppliers,
            'sort'           => $params->getSort(),
            'facet_service'  => $searchService->facets($resultset, $facetFilters),
            'search_time'    => round((microtime(true) - $start), 2),
            'surveys'        => $searchService->surveys($resultset),
            'price_text'     => new PriceText,
            'searchFormMessageSearchWithoutDates' => $generalSettingsService->getSearchFormMessageSearchWithoutDates(),
        ];

        return $this->render('search/' . ($request->isXmlHttpRequest() ? 'results' : 'search') . '.html.twig', $data);
    }

    /**
     * @Route("/wintersport/thema/{url}/", name="show_theme_nl")
     * @Route("/winter-sports/theme/{url}/", name="show_theme_en")
     * @Breadcrumb(name="themes", title="themes", translate=true, path="themes")
     * @Breadcrumb(name="theme", title="{theme}", translate=true, active=true)
     */
    public function showTheme($url, Request $request)
    {
        $themeService           = $this->get('app.api.theme');
        $searchService          = $this->get('app.api.search');
        $seasonService          = $this->get('app.api.season');
        $generalSettingsService = $this->get('app.api.general.settings');

        try {

            $theme = $themeService->theme($url);

        } catch(NoResultException $exception) {
            throw $this->createNotFoundException('Theme page does not exist (anymore)');
        }

        $start     = microtime(true);
        $converter = new FilterConverter();
        $filters   = $converter->convert($theme->getFilters());

        $params    = $searchService->createParamsFromRequest($request);
        $params->setFilters($filters);

        $resultset   = $searchService->search($params);
        $paginator   = $searchService->paginate($resultset, $params);
        $filterNames = $searchService->getFilterNames($params);

        $seasons  = $seasonService->seasons();
        $seasonId = $seasonService->current()['id'];

        $facetFilters = $this->getFacetFilters($params);

        $this->setupJavascriptParameters($params);

        return $this->render('themes/show.html.twig', [

            'offerPage'      => false,
            'offerBox'       => false,
            'search_time'    => round((microtime(true) - $start), 2),
            'theme'          => $theme,
            'resultset'      => $resultset,
            'paginator'      => $paginator,
            'surveys'        => $searchService->surveys($resultset),
            'price_text'     => new PriceText,
            'filter_manager' => new FilterManager(),
            'filters'        => $params->getFilters() ?: [],
            'custom_filters' => [

                'countries'      => $filterNames['countries'],
                'regions'        => $filterNames['regions'],
                'places'         => $filterNames['places'],
                'accommodations' => $filterNames['accommodations'],
                'suppliers'      => $filterNames['suppliers'],
            ],
            'form_filters'   => [

                'weekend'    => $params->getWeekend(),
                'persons'    => $params->getPersons(),
                'bedrooms'   => $params->getBedrooms(),
                'bathrooms'  => $params->getBathrooms(),
                'freesearch' => $params->getFreesearch(),
            ],
            'weekends'       => $seasonService->futureWeekends($seasons),
            'destination'    => $searchService->hasDestination($params),
            'season'         => $seasonId,
            'facet_service'  => $searchService->facets($resultset, $facetFilters),
            'sort'           => $params->getSort(),
            'searchFormMessageSearchWithoutDates' => $generalSettingsService->getSearchFormMessageSearchWithoutDates(),
        ]);
    }

    /**
     * @Route(path="/search/count", name="search_count", options={"expose": true})
     */
    public function count(Request $request)
    {
        $searchService = $this->get('app.api.search');
        $params        = $searchService->createParamsFromRequest($request);
        $resultset     = $searchService->search($params);
        $paginator     = $searchService->paginate($resultset, $params);

        return new JsonResponse([
            'count' => $paginator->total(),
        ]);
    }

    /**
     * @Route(path="/search/save", name="save_search", options={"expose": true})
     */
    public function save(Request $request)
    {
        $userService   = $this->get('app.api.user');
        $searchService = $this->get('app.api.search');
        $params        = $searchService->createParamsFromRequest($request);
        $user          = $userService->user();
        $f             = $params->getFilters();
        $be            = $params->getBedrooms();
        $ba            = $params->getBathrooms();
        $c             = $params->getCountries();
        $r             = $params->getRegions();
        $pl            = $params->getPlaces();
        $a             = $params->getAccommodations();
        $w             = $params->getWeekend();
        $pe            = $params->getPersons();
        $fs            = $params->getFreesearch();

        if (null !== $user) {
            $result = $userService->saveSearch(['f' => $f, 'be' => $be, 'ba' => $ba, 'c' => $c, 'r' => $r, 'pl' => $pl, 'a' => $a, 'w' => $w, 'pe' => $pe, 'fs' => $fs]);
        }

        $success = [

            'type'    => 'success',
            'message' => 'Your search was successfully saved',
        ];

        $failure = [

            'type'    => 'error',
            'message' => 'Your search was not saved',
        ];

        return new JsonResponse((true === $result ? $success : $failure));
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function reroute($params)
    {
        $reroute       = [];
        $regionService = $this->get('app.api.region');
        $locale        = $this->get('app.concern.locale')->get();
        $converter     = new FilterConverter();

        foreach ($params as $param => $value) {

            $matches = ['f' => []];
            $result  = preg_match('/(?P<param>[a-zA-Z_-]+)(?:(?P<value>[0-9]+))?/i', $param, $matches);
            $data    = $matches['param'] === 'vf_kenm' ? $matches['value'] : $value;
            $data    = (int)$data;

            if ($matches['param'] === 'vf_badk' && $data > 0) {

                $reroute['ba'] = $data;

            } elseif ($matches['param'] === 'fap' && $data > 0) {

                $reroute['pe'] = $data;

            } elseif ($matches['param'] === 'fas' && $data > 0) {

                $reroute['be'] = $data;

            } elseif ($matches['param'] === 'fad' && $data > 0) {

                $reroute['w'] = $data;

            } elseif ($matches['param'] === 'faab') {

                $reroute['o'] = $data;

            } elseif ($matches['param'] === 'fsg') {

                $fsg     = explode(',', $value);
                $regions = [];
                $places  = [];

                foreach ($fsg as $id) {

                    if (substr($id, 0, 2) === 'pl') {
                        $places[] = intval(substr($id, 2));
                    } else {

                        $id = explode('-', $id);
                        $regions[] = (int)array_pop($id);
                    }
                }

                if (count($regions) > 0) {
                    $reroute['r'] = $regions;
                }

                if (count($places) > 0) {
                    $reroute['pl'] = $places;
                }

            } else {

                if (null !== ($filter = $converter->map($matches['param'], $data))) {

                    if (!isset($reroute['f'])) {
                        $reroute['f'] = [];
                    }

                    if (FilterManager::multiple($filter['filter'])) {

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

    /**
     * @return array
     */
    public function saved()
    {
        return $this->get('session')->get('search') ?: [];
    }

    /**
     * @param Params $params
     *
     * @return void
     */
    public function saveToSession(Params $params)
    {
        $session = $this->get('session');
        $save    = [];

        if (false !== ($countries = $params->getCountries())) {
            $save['c'] = $countries;
        }

        if (false !== ($regions = $params->getRegions())) {
            $save['r'] = $regions;
        }

        if (false !== ($places = $params->getPlaces())) {
            $save['pl'] = $places;
        }

        if (false !== ($bedrooms = $params->getBedrooms())) {
            $save['be'] = $bedrooms;
        }

        if (false !== ($weekend = $params->getWeekend())) {
            $save['w'] = $weekend;
        }

        if (false !== ($persons = $params->getPersons())) {
            $save['pe'] = $persons;
        }

        $session->set('search', $save);
    }

    /**
     * @param Params $params
     *
     * @return void
     */
    public function setupJavascriptParameters(Params $params)
    {
        $javascript = $this->get('app.javascript');
        $javascript->set('app.filters.normal',                $params->getFilters());
        $javascript->set('app.filters.custom.countries',      $params->getCountries());
        $javascript->set('app.filters.custom.regions',        $params->getRegions());
        $javascript->set('app.filters.custom.places',         $params->getPlaces());
        $javascript->set('app.filters.custom.accommodations', $params->getAccommodations());
        $javascript->set('app.filters.form.freesearch',       $params->getFreesearch());
        $javascript->set('app.filters.form.weekend',          $params->getWeekend());
        $javascript->set('app.filters.form.persons',          $params->getPersons());
        $javascript->set('app.filters.form.bedrooms',         $params->getBedrooms());
        $javascript->set('app.filters.form.bathrooms',        $params->getBathrooms());
        $javascript->set('app.filters.form.suppliers',        $params->getSuppliers());
        $javascript->set('app.filters.form.sort',             $params->getSort());
        $javascript->set('app.filters.form.offerPage',        $params->getOfferPage());
    }

    /**
     * @param Params $params
     *
     * @return array
     */
    private function getFacetFilters(Params $params)
    {
        $filters = FacetService::activeFilters();
        $paramsFilters = $params->getFilters() ?: [];

        foreach ($paramsFilters as $filter => $activeFilterValues) {

            if (false === FilterManager::multiple($filter)) {

                $filters[$filter] = array_filter($filters[$filter], function($value) use ($activeFilterValues) {
                    return $value === $activeFilterValues;
                });
            }
        }

        return $filters;
    }

    /**
     * @param Request $request
     *
     * @return array|boolean
     */
    private function hasSavedSearch(Request $request)
    {
        $saved   = $this->saved();
        $referer = parse_url($request->headers->get('referer'), PHP_URL_PATH);

        $redirect = false;

        if (count($saved) > 0 && $request->query->count() === 0) {

            if (false === $request->isXmlHttpRequest()) {
                $redirect = true;
            }

            if ($request->query->has('h')) {
                $redirect = false;
            }
        }

        return (true === $redirect ? $saved : false);
    }
}
