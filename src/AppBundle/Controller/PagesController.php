<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Template(":Pages:about.html.twig")
     */
    public function about()
    {
        return [];
    }

    /**
     * @Route("/verzekeringen", name="page_insurances_nl")
     * @Template(":Pages:insurances.html.twig")
     */
    public function insurances()
    {
        return [];
    }

    /**
     * @Route("/veel-gestelde-vragen", name="page_faq_nl")
     * @Template(":Pages:faq.html.twig")
     */
    public function faq()
    {
        return [];
    }

    /**
     * @Route("/algemene-voorwaarden", name="page_terms_nl")
     * @Template(":Pages:terms.html.twig")
     */
    public function terms()
    {
        return [];
    }

    /**
     * @Route("/disclaimer", name="page_disclaimer_nl")
     * @Template(":Pages:disclaimer.html.twig")
     */
    public function disclaimer()
    {
        return [];
    }

    /**
     * @Route("/privacy", name="page_privacy_nl")
     * @Template(":Pages:privacy.html.twig")
     */
    public function privacy()
    {
        return [];
    }
}
