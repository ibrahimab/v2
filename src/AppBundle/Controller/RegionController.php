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
     */
    public function show($regionSlug)
    {
        $regionService        = $this->get('app.api.region');
        $typeService          = $this->get('app.api.type');
        $surveyService        = $this->get('app.api.booking.survey');
        $legacyCmsUserService = $this->get('app.legacy.cmsuser');

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

        $internalInfo = [];
        if ($legacyCmsUserService->shouldShowInternalInfo()) {
            $internalInfo['cmsLinks'][] = [
                'url'          => '/cms_skigebieden.php?show=5&wzt=' . $region->getSeason() . '&5k0=' . $region->getId(),
                'name'         => ($region->getSeason() == 1 ? 'skigebied' : 'regio') . ' bewerken',
                'target_blank' => true
            ];
        }

        return $this->render('regions/show.html.twig', [

            'region'       => $region,
            'country'      => $place->getCountry(),
            'places'       => $places,
            'internalInfo' => $internalInfo,
        ]);
    }

    /**
     * @Route(path="/skigebieden.php", name="all_regions_nl")
     * @Route(path="/regions.php", name="all_regions_en")
     * @Breadcrumb(name="all_regions", title="regions", translate=true, active=true)
     */
    public function all()
    {
        $regionService = $this->get('app.api.region');
        $items         = $regionService->regions($this->container);

        return $this->render('regions/all.html.twig', [
            'items' => $items,
        ]);
    }
}