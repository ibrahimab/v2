<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
 * @Breadcrumb(name="frontpage", title="Frontpage", path="home")
 * @Breadcrumb(name="countries", title="Countries", path="countries", level=1)
 */
class CountriesController extends Controller
{
    /**
     * @Route(path="/wintersport/land/{countrySlug}",      name="country_nl")
     * @Route(path="/winter-sports/country/{countrySlug}", name="country_en")
     * @Breadcrumb(name="show", title="{countryName}", active=true)
     */
    public function show($countrySlug)
    {
        $countryService = $this->get('service.api.country');
        $country        = $countryService->findByLocaleName($countrySlug, $this->getRequest()->getLocale());
        
        if (null === $country) {
            throw $this->createNotFoundException('Country with name = ' . $countrySlug . ' could not be found');
        }
        
        return $this->render('countries/show.html.twig', ['country' => $country]);
    }
}