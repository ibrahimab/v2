<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * AdditionalCostsController
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.5
 */
class AdditionalCostsController extends Controller
{
    /**
     * @Route("/additional-costs/type/{typeId}/{seasonId}/{arrangement}", name="additional_costs_type", defaults={"arrangement": false}, options={"expose": true})
     */
    public function type(Request $request, $typeId, $seasonId, $arrangement)
    {
        $additionalCosts = $this->get('app.api.legacy.additional_costs');
        $costs           = $additionalCosts->getCosts($typeId, 'type', $seasonId, $arrangement);

    	return $this->render('additional-costs/type.html.twig', [
    	    'costs' => (isset($costs['html']) ? $costs['html']['inclusief'] : ''),
    	]);
    }
}