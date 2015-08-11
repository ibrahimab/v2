<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Doctrine\ORM\NoResultException;

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
        $themeService  = $this->get('app.api.theme');
        $filterService = $this->get('app.filter');
        $searchService = $this->get('app.api.search');

        try {

            $theme = $themeService->theme($url);

        } catch(NoResultException $exception) {
            throw $this->createNotFoundException('Theme page does not exist (anymore)');
        }

        $filters  = $filterService->parseThemeFilters($theme);
        $per_page = intval($this->container->getParameter('app')['results_per_page']);
        $page     = intval($request->query->get('p'));
        $page     = ($page === 0 ? $page : ($page - 1));

        $resultset = $searchService->build()
                                   ->limit($per_page)
                                   ->page($page)
                                   ->sort(SearchBuilder::SORT_BY_TYPE_SEARCH_ORDER, SearchBuilder::SORT_ORDER_ASC)
                                   ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0)
                                   ->filter($filters)
                                   ->search();

        $typeIds = $resultset->allTypeIds();
        $prices  = [];
        $offers  = [];

        // foreach ($paginator as $accommodation) {
        //
        //     $types = $accommodation->getTypes();
        //     foreach ($types as $type) {
        //         $typeIds[] = $type->getId();
        //     }
        // }

        if (count($typeIds) > 0) {

            $pricesService = $this->get('old.prices.wrapper');
            $prices        = $pricesService->get($typeIds);

            $priceService  = $this->get('app.api.price');
            $offers        = $priceService->offers($typeIds);
        }

        return $this->render('themes/show.html.twig', [

            'theme'     => $theme,
            'resultset' => $resultset,
            'prices'    => $prices,
            'offers'    => $offers,
            'season'    => 27,
        ]);
    }
}