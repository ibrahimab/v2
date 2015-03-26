<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitemapController extends Controller
{
    /**
     * @Route("/sitemap", name="sitemap")
     * @Template(":Sitemap:index.html.twig")
     */
    public function index()
    {
        return [];
    }
}