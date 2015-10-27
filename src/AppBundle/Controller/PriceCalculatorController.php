<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\PriceCalculator\FormService;
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
     * @Route(name="price_calculator_step_one_nl", path="/types/{typeId}/prijs-berekenen", requirements={
     *     "typeId": "\d+"
     * })
     * @Route(name="old_calculate_price_form", path="/calc.php")
     * @Method("GET")
     * @Breadcrumb(name="show_country",    title="{countryName}",         path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",     title="{regionName}",          path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",      title="{placeName}",           path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",       title="{accommodationName}",   path="show_type",    pathParams={"beginCode", "typeId"})
     * @Breadcrumb(name="calculate_price", title="calculate-price-title", translate=true,      active=true)
     */
    public function stepOne(Request $request, $typeId = null)
    {
        // redirect old url to new one
        if (false !== ($response = $this->redirectOldRoute($request))) {
            return $response;
        }

        // get type
        $type = $this->get('app.api.type')->findById($typeId);

        if (null === $type) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }
        
        $priceService = $this->get('app.api.price');
        $weekends     = $priceService->getAvailableWeekends($type);
        $persons      = $priceService->getBookablePersons($type->getId(), $weekends);
        
        $weekendsFormatted = [];
        $date              = new \DateTime();
        $locale            = $request->getLocale();
        $timezone          = $date->getTimezone()->getName();
        $formatter         = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, $timezone, \IntlDateFormatter::GREGORIAN);
        $formatter->setPattern('eeee dd MMMM y');
        
        foreach ($weekends as $key => $weekend) {
            $weekendsFormatted[$weekend] = $formatter->format($date->setTimestamp($weekend));
        }
        
        $personsFormatted = [];
        $translator       = $this->get('translator');
        foreach ($persons as $person) {
            $personsFormatted[$person] = $person . ' ' . strtolower($translator->trans('person' . ($person > 1 ? 's' : '')));
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson($request->query->get('pe', null))
                          ->setPersons($personsFormatted)
                          ->setWeekend($request->query->get('w', null))
                          ->setWeekends($weekendsFormatted);
                        
        return $this->render('price_calculator/step_one.html.twig', [

            'type'    => $type,
            'form'    => $calculatorService->getFormService()->create(FormService::FORM_STEP_ONE)->createView(),
        ]);
    }

    /**
     * @Route(name="price_calculator_step_two_nl", path="/types/{typeId}/prijs-berekenen", requirements={
     *     "typeId": "\d+"
     * })
     * @Method("POST")
     * @Breadcrumb(name="show_country",    title="{countryName}",         path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",     title="{regionName}",          path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",      title="{placeName}",           path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",       title="{accommodationName}",   path="show_type",    pathParams={"beginCode", "typeId"})
     * @Breadcrumb(name="calculate_price", title="calculate-price-title", translate=true,      active=true)
     */
    public function stepTwo(Request $request, $typeId)
    {
        // get type
        $type = $this->get('app.api.type')->findById($typeId);
        
        if (null === $type) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }
        
        $optionService     = $this->get('app.api.option');
        $options           = $optionService->options($type->getAccommodationId());
        
        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setOptions($options);
        
        return $this->render('price_calculator/step_two.html.twig', [
            
            'type' => $type,
            'form' => $calculatorService->getFormService()->create(FormService::FORM_STEP_TWO)->createView(),
        ]);
    }
    
    /**
     * @Route(name="price_calculator_step_three_nl", path="/types/{typeId}/prijs-berekend", requirements={
     *     "typeId": "\d+"
     * })
     * @Method("POST")
     * @Breadcrumb(name="show_country",    title="{countryName}",          path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",     title="{regionName}",           path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",      title="{placeName}",            path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",       title="{accommodationName}",    path="show_type",    pathParams={"beginCode", "typeId"})
     * @Breadcrumb(name="calculate_price", title="price-calculated-title", translate=true,      active=true)
     */
    public function stepThree(Request $request, $typeId)
    {
        return $this->render('price_calculator/step_three.html.twig');
    }

    /**
     * @param Request $request
     * @return boolean|Response
     */
    public function redirectOldRoute(Request $request)
    {
        // checking if requested path is actually the old one
        if (rtrim($request->getPathInfo(), '/calc.php') === '') {

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