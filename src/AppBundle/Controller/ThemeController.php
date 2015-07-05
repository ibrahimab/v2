<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\WebsiteConcern;
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
     * @Route("/themas.php", name="themes_nl")
     * @Route("/themes.php", name="themes_en")
     * @Breadcrumb(name="themes", title="themes", translate=true, active=true)
     */
    public function index()
    {
        $website = $this->get('app.concern.website');

        if (true === in_array($website->type(), [WebsiteConcern::WEBSITE_TYPE_ITALISSIMA, WebsiteConcern::WEBSITE_TYPE_SUPER_SKI, WebsiteConcern::WEBSITE_TYPE_VENTURASOL])) {
            throw $this->createNotFoundException('Theme page is not available for this website type');
        }

        $themeService = $this->get('app.api.theme');
        $themes       = $themeService->themes();

        return $this->render('themes/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    /**
     * @Route("/wintersport/thema/{url}/", name="show_theme_nl")
     * @Route("/winter-sports/theme/{url}/", name="show_theme_en")
     * @Breadcrumb(name="theme", title="theme", translate=true, active=true)
     */
    public function show()
    {
        return $this->render('themes/show.html.twig');
    }
}