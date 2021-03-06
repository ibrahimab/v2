<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;

/**
 * PlacesController
 *
 * This controller handles all the place specific pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="all_regions", title="regions", translate=true, path="all_regions")
 */
class PlacesController extends Controller
{
    /**
     * @Route(path="/wintersport/plaats/{placeSlug}", name="show_place_nl", options={"expose": true})
     * @Route(path="/wintersport/place/{placeSlug}",  name="show_place_en", options={"expose": true})
     * @Breadcrumb(name="show_country", title="{countryName}", path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region", title="{regionName}", path="show_region", pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place", title="{placeName}", active=true)
     * @Template(":places:show.html.twig")
     */
    public function show($placeSlug)
    {
        $placeService         = $this->get('app.api.place');
        $typeService          = $this->get('app.api.type');
        $surveyService        = $this->get('app.api.booking.survey');
        $priceService         = $this->get('app.api.price');
        $legacyCmsUserService = $this->get('app.legacy.cmsuser');
        $locale               = $this->get('app.concern.locale')->get();

        try {

            $place = $placeService->findByLocaleSeoName($placeSlug, $locale);

        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Place could not be found');
        }

        $types        = $placeService->getTypes($place->getId(), 3) ?: [];
        $typesCount   = $typeService->countByPlace($place);
        $surveyStats  = $surveyService->statsByPlace($place);
        $offers       = [];

        $place->setTypesCount($typesCount);

        if (($total = count($surveyStats)) > 0) {

            $place->setRatingsCount(array_sum(array_map('intval', array_column($surveyStats, 'surveyCount'))));
            $place->setAverageRatings(
                round((array_sum(array_map('floatval', array_column($surveyStats, 'surveyAverageOverallRating'))) / $total), 1)
            );
        }

        if (count($types) > 0) {
            $offers = $priceService->offers(array_keys($types));
        }

        $internalInfo = [];
        if (!$legacyCmsUserService->shouldShowInternalInfo()) {

            $internalInfo['cmsLinks'][] = [

                'url'          => '/cms_plaatsen.php?show=4&wzt=' . $place->getSeason() . '&4k0=' . $place->getId(),
                'name'         => 'plaats bewerken',
                'target_blank' => true
            ];
        }

        return [

            'place'        => $place,
            'country'      => $place->getCountry(),
            'region'       => $place->getRegion(),
            'types'        => $types,
            'offers'       => $offers,
            'internalInfo' => $cmsLinks,
            'surveys'      => $surveyService->normalize($surveyStats),
        ];
    }
}
