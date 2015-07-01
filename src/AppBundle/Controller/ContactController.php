<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/contact.php", name="contact")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        return $this->render('contact/new.' . $request->getLocale() . '.html.twig');
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