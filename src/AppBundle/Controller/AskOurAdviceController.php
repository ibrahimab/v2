<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * AskOurAdviceController
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class AskOurAdviceController extends Controller
{
    /**
     * @Route("/vraag-ons-advies.php", name="ask_our_advice_nl")
     * @Route("/ask-our-advice.php", name="ask_our_advice_en")
     * @Breadcrumb(name="ask_our_advice", title="Vraag ons advies", translate=true, path="ask_our_advice", active=true)
     */
    public function index()
    {
        return $this->render('ask-our-advice/index.html.twig', [

        ]);
    }
}
