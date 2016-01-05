<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * WeekendskiController
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 *
 * @Breadcrumb(name="weekendski", title="weekendski", translate=true, path="weekendski")
 */
class WeekendskiController extends Controller
{
    /**
     * @Route("/weekendski.php", name="weekendski_nl")
     * @Route("/weekendski.php", name="weekendski_en")
     */
    public function index()
    {
    }
}