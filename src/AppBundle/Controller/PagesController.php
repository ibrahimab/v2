<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $highlightService = $this->get('service.api.highlight');
        $config           = $this->container->getParameter('app');
        $highlights       = $highlightService->displayable(['limit' => $config['service']['api']['highlight']['limit']]);
        
        return $this->render('home/index.html.twig', [
            'highlights' => $highlights
        ]);
    }
}
