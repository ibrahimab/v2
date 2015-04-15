<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;

/**
 * CountriesController
 *
 * This controller handles all the country specific pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class CountriesController extends Controller
{
    /**
     * @Route(path="/wintersport/landen",      name="show_countries_nl")
     * @Route(path="/winter-sports/countries", name="show_countries_en")
     * @Breadcrumb(name="countries", title="countries", translate=true, active=true)
     * @Template(":Countries:index.html.twig")
     */
    public function index()
    {
        return [];
    }
    
    /**
     * @Route(path="/wintersport/land/{countrySlug}/{sort}",      name="show_country_nl", defaults={"sort"="alpha"}, options={"expose"=true})
     * @Route(path="/winter-sports/country/{countrySlug}/{sort}", name="show_country_en", defaults={"sort"="alpha"}, options={"expose"=true})
     * @Breadcrumb(name="countries", title="countries", translate=true, path="show_countries")
     * @Breadcrumb(name="show_country", title="{countryName}", active=true)
     */
    public function show($countrySlug, $sort)
    {
        $countryService = $this->get('service.api.country');
        $typeService    = $this->get('service.api.type');
        $surveyService  = $this->get('service.api.booking.survey');

        try {
            
            $country        = $countryService->findByLocaleName($countrySlug, $this->getRequest()->getLocale(), $sort);
            
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Country with name = ' . $countrySlug . ' could not be found');
        }
        
        $places         = $country->getPlaces();
        $allRegions     = $places->map(function($place) { return $place->getRegion(); })->toArray();
        $regions        = [];
        $typesCount     = $typeService->countByRegions($allRegions);
        $stats          = $surveyService->statsByCountry($country);
        
        // set stats for country
        if (count($typesCount) > 0) {
            $country->setTypesCount(array_sum($typesCount));
        }
        
        if (isset($stats['surveyCount'])) {
            $country->setRatingsCount(intval($stats['surveyCount']));
        }
        
        if (isset($stats['surveyAverageOverallRating'])) {
            $country->setAverageRatings(round($stats['surveyAverageOverallRating'], 1));
        }
        
        foreach ($allRegions as $region) {
            
            if (true === array_key_exists($region->getId(), $typesCount)) {
                
                $region->setTypesCount($typesCount[$region->getId()]);
                $regions[] = $region;
            }
        }
        
        if ($sort === 'accommodations') {
         
            /**
             * Resorting array if sort option is 'accommodations'
             */
            usort($regions, function(RegionServiceEntityInterface $a, RegionServiceEntityInterface $b) {
                return ($a->getTypesCount() === $b->getTypesCount() ? 0 : (($a->getTypesCount() > $b->getTypesCount()) ? -1 : 1));
            });
        }
        
        return $this->render('countries/show.html.twig', ['country' => $country, 'regions' => $regions, 'sort' => $sort]);
    }
}