<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * PagesController
 *
 * This controller handles all the normal pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class PagesController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template(":Pages:home.html.twig")
     */
    public function home()
    {
        $config           = $this->container->getParameter('app');
        $surveyService    = $this->get('service.api.survey');
        $highlightService = $this->get('service.api.highlight');
        
        $highlights       = $highlightService->displayable(['limit' => $config['service']['api']['highlight']['limit']]);
        $types            = [];
        
        foreach ($highlights as $highlight) {
            
            $type                  = $highlight->getType();
            $types[$type->getId()] = $type;
        }
        
        $surveyStats = $surveyService->statsByTypes($types);
        foreach ($surveyStats as $surveyStat) {
            
            $types[$surveyStat['typeId']]->setSurveyCount($surveyStat['surveyCount']);
            $types[$surveyStat['typeId']]->setSurveyAverageOverallRating($surveyStat['surveyAverageOverallRating']);
        }
        
        return [
            'highlights' => $highlights
        ];
    }
    
    /**
     * @Route("/over-ons", name="page_about_nl")
     * @Breadcrumb(name="about", title="about", translate=true, active=true)
     * @Template(":Pages:about.html.twig")
     */
    public function about()
    {
        return [];
    }

    /**
     * @Route("/verzekeringen", name="page_insurances_nl")
     * @Breadcrumb(name="insurances", title="insurances", translate=true, active=true)
     * @Template(":Pages:insurances.html.twig")
     */
    public function insurances()
    {
        return [];
    }

    /**
     * @Route("/veel-gestelde-vragen", name="page_faq_nl")
     * @Breadcrumb(name="faq", title="faq", translate=true, active=true)
     * @Template(":Pages:faq.html.twig")
     */
    public function faq()
    {
        return [];
    }

    /**
     * @Route("/algemene-voorwaarden", name="page_terms_nl")
     * @Breadcrumb(name="terms", title="terms", translate=true, active=true)
     * @Template(":Pages:terms.html.twig")
     */
    public function terms()
    {
        return [];
    }

    /**
     * @Route("/disclaimer", name="page_disclaimer_nl")
     * @Breadcrumb(name="disclaimer", title="disclaimer", translate=true, active=true)
     * @Template(":Pages:disclaimer.html.twig")
     */
    public function disclaimer()
    {
        return [];
    }

    /**
     * @Route("/privacy", name="page_privacy_nl")
     * @Breadcrumb(name="privacy", title="privacy", translate=true, active=true)
     * @Template(":Pages:privacy.html.twig")
     */
    public function privacy()
    {
        return [];
    }
}
