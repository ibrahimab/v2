<?php
namespace AppBundle\Controller;


// use       AppBundle\Entity\Country\Country;

use       AppBundle\Entity\GeneralSettings\GeneralSettings;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{
    /**
     * @Route("/nieuwsbrief", name="newsletter")
     * @Template(":pages:newsletter.html.twig")
     * @Method("GET")
     */
    public function show()
    {
        $generalSettings = $this->get('app.api.general.settings');

        return [
            'newsletters' => $generalSettings->getNewsletters(),
        ];
    }

}