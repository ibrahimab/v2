<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
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
        
        return $this->render('home/index.html.twig', [
            'highlights' => $highlights
        ]);
    }
}
