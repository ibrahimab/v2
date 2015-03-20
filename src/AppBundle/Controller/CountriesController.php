<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CountriesController extends Controller
{
    /**
     * @Route(path="/wintersport/land/{slug}",      name="country_nl")
     * @Route(path="/winter-sports/country/{slug}", name="country_en")
     */
    public function show($slug)
    {
        $countryService = $this->get('service.api.country');
        $country        = $countryService->find(['name' => $slug]);
        
        dump($country);
        
        return $this->render('countries/show.html.twig', ['country' => $country]);
    }
}