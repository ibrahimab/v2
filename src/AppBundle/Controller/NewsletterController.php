<?php
namespace AppBundle\Controller;

use       AppBundle\Entity\GeneralSettings\GeneralSettings;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * NewsletterController
 *
 * This controller handles the newsletter page
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 * @version 0.0.1
 * @since   0.0.1
 *
 */
class NewsletterController extends Controller
{
    /**
     * @Route("/nieuwsbrief", name="newsletter")
     * @Template(":pages:newsletter.html.twig")
     * @Method("GET")
     */
    public function show(Request $request)
    {

        $generalSettings = $this->get('app.api.general.settings');

        $sitename = $this->get('app.concern.website')->name();

        $formFieldNames = $this->get('app.newsletter');

        // use post-value from homepage newsletter-form
        $postEmail = $request->get('email');

        // after succesful subscribe
        $subscribeSucces = ((int)$request->get('ok', 0) === 1);

        return [
            'newsletters' => $generalSettings->getNewsletters(),
            'sitename' => $sitename,
            'per_wanneer' => $formFieldNames->get("per_wanneer"),
            'email' => $formFieldNames->get("email"),
            'voornaam' => $formFieldNames->get("voornaam"),
            'tussenvoegsel' => $formFieldNames->get("tussenvoegsel"),
            'achternaam' => $formFieldNames->get("achternaam"),
            'formEncId' => $formFieldNames->get("formEncId"),
            'formname' => $formFieldNames->get("formname"),
            'subscribeAfterSeason' => $formFieldNames->get("subscribeAfterSeason"),
            'postEmail' => $postEmail,
            'subscribeSucces' => $subscribeSucces,
        ];
    }

}