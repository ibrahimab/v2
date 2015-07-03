<?php
namespace AppBundle\Controller;

use       AppBundle\Entity\GeneralSettings\GeneralSettings;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function show()
    {

        $generalSettings = $this->get('app.api.general.settings');

        $sitename = $this->get('app.concern.website')->name();

        $formFieldNames = $this->get('app.newsletter');

        $postEmail = $_POST["email"];

        // after succesful subscribe
        if($_GET["ok"]) {
            $subscribeSucces = true;
        } else {
            $subscribeSucces = false;
        }

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