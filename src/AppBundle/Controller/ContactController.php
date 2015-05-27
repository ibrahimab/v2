<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     * @Template(":contact:new.html.twig")
     * @Method("GET")
     */
    public function newAction()
    {
        return [];
    }

    /**
     * @Route("/contact", name="create_contact")
     * @Method("POST")
     */
    public function create()
    {
        return [];
    }
}