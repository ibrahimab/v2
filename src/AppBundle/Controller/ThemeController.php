<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * ThemeController
 *
 * This controller handles the themes
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class ThemeController extends Controller
{
    /**
     * @Route("/wintersport/thema/{url}/", name="theme_nl")
     * @Route("/winter-sports/theme/{url}/", name="theme_en")
     * @Breadcrumb(name="theme", title="theme", translate=true, active=true)
     */
    public function show()
    {
        return $this->render('themes/show.html.twig');
    }
}