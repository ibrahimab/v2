<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;

/**
 * ThemeController
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
class OptionController extends Controller
{
    /**
     * @Route("/themas.php", name="new_option_nl")
     * @Route("/available.php", name="new_option_en")
     * @Breadcrumb(name="new_option", title="new-option", translate=true, active=true)
     * @Method("GET")
     */
    public function newAction()
    {
    }
}