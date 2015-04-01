<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
 * @Breadcrumb(name="countries", title="countries", translate=true, path="show_countries")
 */
class RegionController extends Controller
{
    /**
     * @Route(path="/wintersport/skigebied/{regionSlug}", name="show_region_nl")
     * @Route(path="/winter-sports/region/{regionSlug}",  name="show_region_en")
     * @Breadcrumb(name="show_country", title="{countryName}", path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region", title="{regionName}", active=true)
     * @Template(":Region:show.html.twig")
     */
    public function show($regionSlug)
    {
        $regionService  = $this->container->get('service.api.region');
        $regionAndPlace = $regionService->findByLocaleSeoName($regionSlug, $this->getRequest()->getLocale());

        return [
            
            'region' => $regionAndPlace[0],
            'place'  => $regionAndPlace[1]
        ];
    }
}