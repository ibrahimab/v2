<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BlogController
 *
 * This controller handles the blog
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Breadcrumb(name="blog_index", title="blog_index", translate=true, active=true)
     * @Template(":blog:index.html.twig")
     * @Method("Get")
     */
    public function index()
    {
        return [];
    }
}