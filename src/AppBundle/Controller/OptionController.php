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
 */
class OptionController extends Controller
{
    /**
     * @Route("/options/{optionId}", name="show_option_nl")
     */
    public function show($optionId)
    {
        $optionService = $this->get('app.api.option');
        $option        = $optionService->option($optionId);
        
        return $this->render('option/show.html.twig', [
            'option' => $option,
        ]);
    }
}