<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\Response;
use       Symfony\Component\HttpFoundation\JsonResponse;

/**
 * CountriesController
 *
 * This controller handles all the country specific pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class PriceCalculatorController extends Controller
{
    /**
     * @Route(name="calculate_price_form_nl", path="/types/{typeId}/prijs-berekenen", requirements={
     *     "typeId": "\d+"
     * })
     * @Breadcrumb(name="show_country",    title="{countryName}",         path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",     title="{regionName}",          path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",      title="{placeName}",           path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",       title="{accommodationName}",   path="show_type",    pathParams={"beginCode", "typeId"})
     * @Breadcrumb(name="calculate_price", title="calculate-price-title", translate=true,      active=true)
     */
    public function create(Request $request, $typeId)
    {
        // redirect old url to new one
        if (false !== ($response = $this->redirectOldRoute($request))) {
            return $response;
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setTypeId($typeId);

        if (null === $calculatorService->type()) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }

        $locale   = $request->getLocale();
        $type     = $calculatorService->type();
        $place    = $type->getAccommodation()->getPlace();
        $options  = $calculatorService->options();
        $persons  = [];
        $weekends = [];
        $stepOne  = new \AppBundle\Entity\PriceCalculator\StepOne([

            'type'     => $type->getLocaleName($locale),
            'place'    => $place->getLocaleName($locale),
            'person'   => $request->query->get('pe', null),
            'persons'  => $persons,
            'weekend'  => $request->query->get('w', null),
            'weekends' => $weekends,
        ]);

        $form = $this->createForm($this->get('app.form.price_calculator.step_one'), $stepOne);

        return $this->render('price_calculator/index.html.twig', [

            'type'    => $type,
            'options' => $options,
            'form'    => $form->createView(),
        ]);
    }

    public function store()
    {
        return new JsonResponse([
            'type' =>'success',
        ]);
    }

    /**
     * @param Request $request
     * @return boolean|Response
     */
    public function redirectOldRoute(Request $request)
    {
        // checking if requested path is actually the old one
        if (null !== ($path = $request->get('pathInfo', null)) && rtrim($path, '/calc.php') === '') {

            $query  = $request->query;
            $params = ['typeId' => $query->get('tid', null)];

            if ($query->get('d', '') !== '') {

                // normal saturday - saturday weekend definition
                $params['w'] = $query->get('d');

            } elseif ($query->get('flad', '') !== '') {

                // flexible period
                $params['w'] = $query->get('flad');
            }

            if ($query->get('ap', '') !== '') {

                // persons
                $params['p'] = $query->get('ap');
            }

            return $this->redirectToRoute('calculate_price_form_' . $request->getLocale(), $params, 301);
        }

        return false;
    }
}