<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="contact", title="contact", translate=true, path="contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/contact.php", name="contact")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        return $this->render('contact/new.html.twig', [

            'website_concern' => $this->get('app.concern.website'),
        ]);
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