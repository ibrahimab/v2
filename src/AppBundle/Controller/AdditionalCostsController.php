<?php
namespace AppBundle\Controller;
use       AppBundle\Old\Loader;
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
        $constants = $this->container->get('old.constants');
        $constants->setup();

        Loader::loadTxt($request->getLocale(), $this->get('app.concern.website'));

        $config = $this->get('old.configuration');
        $accInfo = new \AccInfo($typeId, $aankomstdatum, $aantalpersonen);
        $accInfo->setConfiguration($config);
        $accInfo->setLanguageField($config->ttv);
        $accInfo->setResale(null);
        $accInfo->loadTranslations([

            'personen'                     => txt('personen'),
            'persoon'                      => txt('persoon'),
            'pers'                         => txt('pers'),
            'menu_accommodatie'            => txt('menu_accommodatie'),
            'canonical_accommodatiepagina' => txt('canonical_accommodatiepagina'),
            'menu_plaats'                  => txt('menu_plaats'),
        ]);

    	$additionalCosts = new \bijkomendekosten($typeId, 'type');
        $additionalCosts->setConfiguration($config);
    	$additionalCosts->setRedis($this->get('old.redis'));
        $additionalCosts->accinfo    = $accInfo->process()->result();
    	$additionalCosts->seizoen_id = $seasonId;

    	if (true === $arrangement) {
    		$additionalCosts->arrangement = true;
    	}

    	return $this->render('additional-costs/type.html.twig', [
    	    'costs' => $additionalCosts->get_costs()['html']['inclusief'],
    	]);
    }
}