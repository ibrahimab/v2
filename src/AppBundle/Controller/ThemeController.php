<?php
namespace AppBundle\Controller;

use AppBundle\Concern\WebsiteConcern;
use AppBundle\Annotation\Breadcrumb;
use AppBundle\Service\Api\Search\SearchBuilder;
use AppBundle\Service\Api\Search\FilterBuilder;
use AppBundle\Service\Api\Search\Filter\Converter;
use AppBundle\Service\Api\Search\Filter\Manager   as FilterManager;
use AppBundle\Service\Api\Search\FacetService;
use AppBundle\Service\Api\Search\Result\Sorter;
use AppBundle\Service\Api\Search\Result\PriceText;
use AppBundle\Service\Api\Search\Params;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;

/**
 * ThemeController
 *
 * This controller handles the themes
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class ThemeController extends Controller
{
    /**
     * @Route("/themas.php", name="themes_nl")
     * @Route("/themes.php", name="themes_en")
     * @Breadcrumb(name="themes", title="themes", translate=true, active=true)
     */
    public function index()
    {
        $website = $this->get('app.concern.website');

        if (true === in_array($website->type(), [WebsiteConcern::WEBSITE_TYPE_ITALISSIMA, WebsiteConcern::WEBSITE_TYPE_SUPER_SKI, WebsiteConcern::WEBSITE_TYPE_VENTURASOL])) {
            throw $this->createNotFoundException('Theme page is not available for this website type');
        }

        $themeService = $this->get('app.api.theme');
        $themes       = $themeService->themes();

        return $this->render('themes/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    /**
     * @Route("/wintersport/thema/{url}/", name="show_theme_nl")
     * @Route("/winter-sports/theme/{url}/", name="show_theme_en")
     * @Breadcrumb(name="themes", title="themes", translate=true, path="themes")
     * @Breadcrumb(name="theme", title="{theme}", translate=true, active=true)
     */
    public function show($url, Request $request)
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
        $converter = new Converter();
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
    }
}