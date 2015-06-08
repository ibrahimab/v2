<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;

/**
 * RegionController
 *
 * This controller handles all the country specific pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class RegionController extends Controller
{
    /**
     * @Route(path="/wintersport/skigebied/{regionSlug}", name="show_region_nl", options={"expose": true})
     * @Route(path="/winter-sports/region/{regionSlug}",  name="show_region_en", options={"expose": true})
     * @Breadcrumb(name="countries", title="countries", translate=true, path="show_countries")
     * @Breadcrumb(name="show_country", title="{countryName}", path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region", title="{regionName}", active=true)
     * @Template(":region:show.html.twig")
     */
    public function show($regionSlug)
    {
        $regionService   = $this->get('service.api.region');
        $typeService     = $this->get('service.api.type');
        $surveyService   = $this->get('service.api.booking.survey');
        
        $places          = [];
        $allPlaces       = $regionService->findByLocaleSeoName($regionSlug, $this->getRequest()->getLocale());

        if (count($allPlaces) === 0) {
            throw $this->createNotFoundException('Could not find region with slug=' . $regionSlug);
        }

        $place           = $allPlaces[0];
        $region          = $place->getRegion();
        $typesCount      = $typeService->countByRegion($region);
        $stats           = $surveyService->statsByRegion($region);

        if (count($typesCount) > 0) {
            $region->setTypesCount(array_sum($typesCount));
        }
        
        if (isset($stats['surveyCount'])) {
            $region->setRatingsCount(intval($stats['surveyCount']));
        }
        
        if (isset($stats['surveyAverageOverallRating'])) {
            $region->setAverageRatings(round($stats['surveyAverageOverallRating'], 1));
        }
        
        foreach ($allPlaces as $place) {
            
            if (true === array_key_exists($place->getId(), $typesCount)) {
                
                $place->setTypesCount($typesCount[$place->getId()]);
                $places[] = $place;
            }
        }
        
        return [
            
            'region'  => $region,
            'country' => $place->getCountry(),
            'places'  => $places,
        ];
    }
}