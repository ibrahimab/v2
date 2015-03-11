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
        $service = $this->get('api.type.service');
        $item    = $service->typeRepository->all();
        dump($item);exit;
           
        return $this->render('home/index.html.twig');
    }
}
