<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Entity\Booking\Booking;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * BookingController
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
class BookingController extends Controller
{
    /**
     * @Route("/boeken.php", name="booking_nl")
     * @Breadcrumb(name="theme", title="theme", translate=true, active=true)
     */
    public function index(Request $request)
    {
        return $this->render('booking/index.html.twig', [
            'tid' => $request->get('tid'),
        ]);
    }
}