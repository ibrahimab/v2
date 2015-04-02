<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
 * @Breadcrumb(name="countries", title="countries", translate=true, path="show_countries")
 */
class PlacesController extends Controller
{
    /**
     * @Route(path="/wintersport/plaats/{placeSlug}", name="show_place_nl")
     * @Route(path="/wintersport/place/{placeSlug}",  name="show_place_en")
     * @Breadcrumb(name="show_country", title="{countryName}", path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region", title="{regionName}", path="show_region", pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place", title="{placeName}", active=true)
     * @Template(":Places:show.html.twig")
     */
    public function show($placeSlug)
    {
        $placeService = $this->get('service.api.place');
        $typeService  = $this->get('service.api.type');
        
        $place        = $placeService->findByLocaleSeoName($placeSlug, $this->getRequest()->getLocale());
        $types        = $typeService->findByPlace($place);

        $place->setTypesCount(count($types));
        
        return [
            
            'place'   => $place,
            'country' => $place->getCountry(),
            'region'  => $place->getRegion(),
            'types'   => $types,
        ];
    }
}