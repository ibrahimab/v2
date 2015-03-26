<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Template(":Blog:index.html.twig")
     * @Method("Get")
     */
    public function index()
    {
        return [];
    }
}