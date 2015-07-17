<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Autocomplete\AutocompleteService;
use       AppBundle\Service\FilterService;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * PagesController
 *
 * This controller handles all the normal pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class PagesController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template(":pages:home.html.twig")
     */
    public function home()
    {
        $config               = $this->container->getParameter('app');
        $surveyService        = $this->get('app.api.booking.survey');
        $highlightService     = $this->get('app.api.highlight');
        $homepageBlockService = $this->get('app.api.homepageblock');
        $regionService        = $this->get('app.api.region');
        $placeService         = $this->get('app.api.place');
        $typeService          = $this->get('app.api.type');
        $priceService         = $this->get('app.api.price');
        $seasonService        = $this->get('app.api.season');
        $searchService        = $this->get('app.api.search');
        $searchBuilder        = $searchService->build()
                                              ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0);

        $regions              = $regionService->findHomepageRegions(['limit' => 1]);
        $places               = [];
        $region               = null;
        $offers               = [];

        if (count($regions) > 0) {

            $region      = $regions[0];
            $places      = $placeService->findHomepagePlaces($region, ['limit' => 3]);
            $regionCount = $typeService->countByRegion($region);
            $region->setTypesCount(array_sum($regionCount))
                   ->setPlacesCount(count($regionCount));

            foreach ($places as $place) {

                if (isset($regionCount[$place->getId()])) {
                    $place->setTypesCount($regionCount[$place->getId()]);
                }
            }
        }

        $homepageBlocks       = $homepageBlockService->published();
        $highlights           = $highlightService->displayable(['limit' => $config['service']['api']['highlight']['limit'], 'results_per_row' => $config['service']['api']['highlight']['results_per_row'] ]);
        $types                = [];

        foreach ($highlights as $highlight) {

            $type                  = $highlight->getType();
            $types[$type->getId()] = $type;
        }

        if (count($types) > 0) {

            $surveyStats = $surveyService->statsByTypes($types);
            foreach ($surveyStats as $surveyStat) {

                $types[$surveyStat['typeId']]->setSurveyCount($surveyStat['surveyCount']);
                $types[$surveyStat['typeId']]->setSurveyAverageOverallRating($surveyStat['surveyAverageOverallRating']);
            }

            $offers = $priceService->offers(array_keys($types));
        }

        $groupedHomepageBlocks = ['left' => [], 'right' => []];
        $homepageBlocks_left_counter = 0;
        $homepageBlocks_right_counter = 0;
        foreach ($homepageBlocks as $block) {

            if ($block->getPosition() === HomepageBlockServiceEntityInterface::POSITION_LEFT) {
                $groupedHomepageBlocks['left'][] = $block;
                $homepageBlocks_left_counter++;
            }

            if ($block->getPosition() === HomepageBlockServiceEntityInterface::POSITION_RIGHT) {
                $groupedHomepageBlocks['right'][] = $block;
                $homepageBlocks_right_counter++;
            }
        }

        // check if there are 1 left and 2 right homepageBlocks
        // if not: empty the array
        if( $homepageBlocks_left_counter<>1 or $homepageBlocks_right_counter<>2 ) {
            $groupedHomepageBlocks['left'] = array();
            $groupedHomepageBlocks['right'] = array();
        }

        return [

            'region'         => $region,
            'places'         => $places,
            'highlights'     => $highlights,
            'homepageBlocks' => $groupedHomepageBlocks,
            'offers'         => $offers,
            'weekends'       => $seasonService->weekends($seasonService->seasons()),
            'accommodations' => $searchBuilder->count(),
            'regions'        => $regionService->count(),
        ];
    }

    /**
     * @Route("/wie-zijn-wij.php", name="page_about_nl")
     * @Route("/about-us.php", name="page_about_en")
     * @Breadcrumb(name="about", title="about", translate=true, active=true)
     */
    public function about(Request $request)
    {
        return $this->render('pages/about/' . $request->getLocale() . '.html.twig');
    }

    /**
     * @Route("/verzekeringen.php", name="page_insurances_nl")
     * @Route("/insurance.php", name="page_insurances_en")
     * @Breadcrumb(name="insurances", title="insurances", translate=true, active=true)
     */
    public function insurances(Request $request)
    {
        $seasonService   = $this->get('app.api.season');
        $optionService   = $this->get('app.api.option');
        $locale          = $request->getLocale();
        $app             = $this->container->getParameter('app');
        $seasonConcern   = $this->get('app.concern.season');
        $websiteConcern  = $this->get('app.concern.website');
        $travelInsurance = $optionService->getTravelInsurancesDescription();

        return $this->render('pages/insurances/' . $locale . '.html.twig', [

            'costs'                     => $seasonService->getInsurancesPolicyCosts(),
            'locale'                    => $locale,
            'damages'                   => true,
            'travel_insurance_possible' => $app['travel_insurance_possible'],
            'ten_days_insurance_price'  => ($seasonConcern->get() === SeasonConcern::SEASON_SUMMER ? $app['ten_days_insurance_price_summer'] : $app['ten_days_insurance_price_default']),
            'travelInsurance'           => $travelInsurance,
            'show_sunnycar'             => $websiteConcern->get() === WebsiteConcern::WEBSITE_ITALISSIMA_NL,
        ]);
    }

    /**
     * @Route("/veel-gestelde-vragen", name="page_faq_nl")
     * @Route("/frequently-asked-questions", name="page_faq_en")
     * @Breadcrumb(name="faq", title="faq", translate=true, active=true)
     */
    public function faq()
    {
        $faqService = $this->get('app.api.faq');
        $items      = $faqService->getItems();

        return $this->render('pages/faq.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/algemenevoorwaarden.php", name="page_terms_nl")
     * @Route("/conditions.php", name="page_terms_en")
     * @Breadcrumb(name="terms", title="terms", translate=true, active=true)
     */
    public function conditions(Request $request)
    {
        return $this->render('pages/conditions/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website')
        ]);
    }

    /**
     * @Route("/disclaimer.php", name="page_disclaimer_nl")
     * @Route("/disclaimer.php", name="page_disclaimer_en")
     * @Breadcrumb(name="disclaimer", title="disclaimer", translate=true, active=true)
     */
    public function disclaimer(Request $request)
    {
        return $this->render('pages/disclaimer/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website'),
        ]);
    }

    /**
     * @Route("/privacy-statement.php", name="page_privacy_nl")
     * @Route("/privacy-statement.php", name="page_privacy_en")
     * @Breadcrumb(name="privacy", title="privacy", translate=true, active=true)
     */
    public function privacy(Request $request)
    {
        return $this->render('pages/privacy/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website'),
        ]);
    }

    /**
     * @Route("/werkenbij", name="page_working", requirements={"_locale": "nl"})
     */
    public function working()
    {
        return $this->render('pages/working.html.twig');
    }

    /**
     * @Route("/zoekopdrachten", name="page_searches_nl")
     * @Route("/searches", name="page_searches_en")
     * @Breadcrumb(name="searches", title="page-searches", translate=true, active=true)
     */
    public function searches()
    {
        return $this->render('pages/searches.html.twig', [
            'saved_searches' => $this->container->get('app.api.user')->user()->getSearches(),
        ]);
    }

    /**
     * @Route("/bekeken-accommodaties", name="page_viewed_nl")
     * @Route("/viewed-accommodations", name="page_viewed_en")
     * @Breadcrumb(name="viewed", title="page-viewed", translate=true, active=true)
     */
    public function viewed()
    {
        $typeIds      = $this->get('app.api.user')->user()->getViewed();
        $typeService  = $this->get('app.api.type');
        $priceService = $this->get('app.api.price');

        $offers       = [];
        $types        = [];

        if (count($typeIds) > 0) {

            $types  = $typeService->findById($typeIds);
            $offers = $priceService->offers($typeIds);
        }

        return $this->render('pages/viewed.html.twig', [

            'types'  => $types,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/opgeslagen-accommodatie", name="page_saved_nl")
     * @Route("/saved-accommodation", name="page_saved_en")
     * @Breadcrumb(name="saved", title="page-saved", translate=true, active=true)
     */
    public function saved()
    {
        $typeIds      = $this->get('app.api.user')->user()->getFavorites();
        $typeService  = $this->get('app.api.type');
        $priceService = $this->get('app.api.price');

        $offers       = [];
        $types        = [];

        if (count($typeIds) > 0) {

            $types  = $typeService->findById($typeIds);
            $offers = $priceService->offers($typeIds);
        }

        return $this->render('pages/saved.html.twig', [

            'types'  => $types,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/header", name="page_header")
     */
    public function header()
    {
        return $this->render('pages/header.html.twig');
    }

    /**
     * @Route("/zooverawards2015", name="page_zooverawards")
     */
    public function zooverawards()
    {
        return $this->render('pages/zooverawards.html.twig');
    }
}