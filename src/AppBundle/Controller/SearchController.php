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
        $locale  = $request->getLocale();
        $reroute = $this->reroute($request->query->all());

        if (count($reroute) > 0) {
            return $this->redirectToRoute('search_' . $locale, $reroute, 301);
        }

        if (false !== ($saved = $this->hasSavedSearch($request))) {

            $this->get('session')->remove('search');
            return $this->redirectToRoute('search_' . $locale, $saved, 301);
        }

        $this->saveToSession($request);

        $start                  = microtime(true);
        $surveyService          = $this->get('app.api.booking.survey');
        $seasonService          = $this->get('app.api.season');
        $searchService          = $this->get('app.api.search');
        $generalSettingsService = $this->get('app.api.general.settings');

        $params                 = $searchService->createParamsFromRequest($request);
        $resultset              = $searchService->search($params);
        $paginator              = $searchService->paginate($resultset, $params);
        $destination            = $searchService->hasDestination($params);
        $typeIds                = $searchService->extractTypeIds($resultset);
        $names                  = $searchService->extractNames($resultset, $params);
        $accommodationNames     = $names['accommodations'];

        $this->setupJavascriptParameters($params);

        $seasons  = $seasonService->seasons();
        $seasonId = $seasonService->current()['id'];
        $data     = [

            'season'         => $seasonId,
            'resultset'      => $resultset,
            'paginator'      => $paginator,
            'filters'        => $params->getFilters() ?: [],
            // instance needed to get constants easier from within twig template: constant('const', instance)
            'filter_manager' => new FilterManager(),
            'custom_filters' => [

                'countries'      => $params->getCountries(),
                'regions'        => ($params->getRegions() ? $names['regions'] : []),
                'places'         => ($params->getPlaces() ? $names['places'] : []),
                'accommodations' => $accommodationNames,
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
            $userService->saveSearch(['f' => $f, 'be' => $be, 'ba' => $ba, 'c' => $c, 'r' => $r, 'pl' => $pl, 'a' => $a, 'w' => $w, 'pe' => $pe, 'fs' => $fs]);
        }

        return new JsonResponse([

            'type'    => 'success',
            'message' => 'Your search was successfully saved',
        ]);
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

            if ($matches['param'] === 'vf_badk') {

                $reroute['ba'] = $data;

            } elseif ($matches['param'] === 'fap') {

                $reroute['pe'] = $data;

            } elseif ($matches['param'] === 'fas') {

                $reroute['be'] = $data;

            } elseif ($matches['param'] === 'fad') {

                $reroute['w'] = $data;

            } elseif ($matches['param'] === 'fsg') {

                $destinationIds = explode(',', $value);
                $ids            = [];

                foreach ($destinationIds as $id) {

                    $id = explode('-', $id);
                    $ids[] = (int)array_pop($id);
                }

                $destinations = $regionService->all(['where' => ['id' => $ids]]);

                $reroute['r'] = array_map(function($region) use ($locale) {

                    return $region->getLocaleName($locale);

                }, $destinations);

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
     * @param array $params
     *
     * @return void
     */
    public function saveToSession(Request $request)
    {
        $session = $this->get('session');
        $save    = [];

        if (count($countries = $request->query->get('c', [])) > 0) {
            $save['c'] = $countries;
        }

        if (count($regions = $request->query->get('r', [])) > 0) {
            $save['r'] = $regions;
        }

        if (count($places = $request->query->get('pl', [])) > 0) {
            $save['pl'] = $places;
        }

        if (count($accommodations = $request->query->get('a', [])) > 0) {
            $save['a'] = $accommodations;
        }

        if (count($types = $request->query->get('t', [])) > 0) {
            $save['t'] = $types;
        }

        if (null !== ($bedrooms = $request->query->get('be', null)) && $bedrooms > 0) {
            $save['be'] = $bedrooms;
        }

        if (null !== ($bathrooms = $request->query->get('ba', null)) && $bathrooms > 0) {
            $save['ba'] = $bathrooms;
        }

        if (null !== ($weekend = $request->query->get('w', null)) && $weekend > 0) {
            $save['w'] = $weekend;
        }

        if (null !== ($persons = $request->query->get('pe', null)) && $persons > 0) {
            $save['pe'] = $persons;
        }

        if (null !== ($freesearch = $request->query->get('fs', null)) && $freesearch != '') {
            $save['fs'] = $freesearch;
        }

        if (null !== ($sort = $request->query->get('s', null))) {
            $save['s'] = $sort;
        }

        if (null !== ($page = $request->query->get('p', null))) {

            $save['p'] = $request->query->getInt('p', 0);
            $save['p'] = ($save['p'] === 0 ? $save['p'] : ($save['p'] - 1));
        }

        if (count($filters = $request->query->get('f', [])) > 0) {
            $save['f'] = $filters;
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
        $javascript->set('app.filters.form.sort',             $params->getSort());
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
