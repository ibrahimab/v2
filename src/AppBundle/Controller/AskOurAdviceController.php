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
 * @Breadcrumb(name="ask_our_advice", title="ask_our_advice", translate=true, path="ask_our_advice")
 */
class AskOurAdviceController extends Controller
{
    /**
     * @Route("/vraag-ons-advies.php", name="ask_our_advice_nl")
     * @Route("/ask-our-advice.php", name="ask_our_advice_en")
     */
    public function index()
    {
    }
}