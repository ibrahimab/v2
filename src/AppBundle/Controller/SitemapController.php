<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\Country\Country;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitemapController extends Controller
{
    /**
     * @Route("/sitemap", name="sitemap")
     * @Template(":sitemap:index.html.twig")
     */
    public function index()
    {
        $countryService = $this->get('app.api.country');
        $websiteConcern = $this->get('app.concern.website');
        $currentWebsite = $websiteConcern->get();
        $regions        = [];

        if (true === in_array($currentWebsite, [WebsiteConcern::WEBSITE_ITALISSIMA_NL, WebsiteConcern::WEBSITE_ITALISSIMA_BE])) {

            $parameters = $this->container->getParameter('app');
            $countryId = $parameters['countries'][$currentWebsite];

            $country = new Country($countryId);
            $country = $countryService->findRegions($country);
            $places  = $country->getPlaces();
            $regions = $places->map(function(PlaceServiceEntityInterface $place) {
                return $place->getRegion();
            })->toArray();
        }

        $themes = [];
        if (false === in_array($currentWebsite, [WebsiteConcern::WEBSITE_SUPER_SKI, WebsiteConcern::WEBSITE_VALLANDRY_NL, WebsiteConcern::WEBSITE_CHALETS_IN_VALLANDRY_COM]) && $websiteConcern->type() !== WebsiteConcern::WEBSITE_TYPE_ITALISSIMA) {

            $themeService = $this->get('app.api.theme');
            $themes       = $themeService->themes();
        }

        return $this->render('sitemap/index.html.twig', [

            'website' => $websiteConcern,
            'regions' => $regions,
            'themes'  => $themes,
        ]);
    }
}