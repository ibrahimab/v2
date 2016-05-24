<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Entity\Booking\Booking;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * OptionRequestController
 *
 * This controller handles the option requests
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 *
 */
class OptionRequestController extends Controller
{
    /**
     * @Route("/beschikbaarheid.php", name="show_optionrequest")
     */
    public function index(Request $request)
    {
        return $this->render('option-request/index.html.twig', [
            'tid'               => $request->get('tid'),
            'arrival_date'      => $request->get('d'),
            'number_of_persons' => $request->get('ap'),
            'back'              => $request->get('back'),
        ]);
    }
}
