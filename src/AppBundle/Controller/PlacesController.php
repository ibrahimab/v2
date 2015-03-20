<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlacesController extends Controller
{
    /**
     * @Route(path="/wintersport/plaats", name="place_nl")
     * @Route(path="/wintersport/place",  name="place_en")
     */
    public function show()
    {
        return $this->render('places/show.html.twig', [
            'text' => $this->getRequest()->getLocale() . ' ' . $this->getRequest()->getHost()
        ]);
    }
}