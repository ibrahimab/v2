<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\PriceCalculator\FormService;
use       AppBundle\Entity\Booking\Booking as BookingEntity;
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
     * @Route(name="price_calculator_step_one_nl", path="/prijs-berekenen/{typeId}", requirements={
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

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson($request->query->get('pe', null))
                          ->setWeekend($request->query->get('w', null));

        return $this->render('price_calculator/step_one.html.twig', [

            'type'    => $type,
            'form'    => $calculatorService->getFormService()->create(FormService::FORM_STEP_ONE)->createView(),
        ]);
    }

    /**
     * @Route(name="price_calculator_step_two_nl", path="/prijs-berekenen/{typeId}/stap-2", requirements={
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
        $type = $this->get('app.api.type')->findById($typeId);

        if (null === $type) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }

        if ($request->isMethod('POST')) {

            $calculatorService = $this->get('app.price_calculator.calculator');
            $calculatorService->setType($type);

            $formService       = $calculatorService->getFormService();
            $form              = $formService->create(FormService::FORM_STEP_ONE);
            $form->handleRequest($request);

            if ($form->isValid()) {

                $accommodationService = $this->get('app.accommodation');
                $data                 = $form->getData();
                $typeData             = $accommodationService->get($type->getId(), $data->weekend, $data->person);

                $booking = new BookingEntity();
                $booking->setCalc(1)
                        ->setTypeId($typeData['type_id'])
                        ->setSeasonId($typeData['season'])
                        ->setPersons($data->person)
                        ->setArrivalAt($data->weekend);

                $bookingService = $this->get('app.booking');
                $bookingId      = $bookingService->setBooking($booking)
                                                 ->create($typeData);

            } else {

                return $this->redirectToRoute('price_calculator_step_one_' . $this->get('app.concern.locale')->get(), [
                    'typeId' => $typeData['type_id'],
                ]);
            }
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson((int)$request->request->get('step_one')['person'])
                          ->setWeekend((int)$request->request->get('step_one')['weekend']);

        return $this->render('price_calculator/step_two.html.twig', [

            'type' => $type,
            'form' => $calculatorService->getFormService()->create(FormService::FORM_STEP_TWO)->createView(),
        ]);
    }

    /**
     * @Route(name="price_calculator_step_three_nl", path="/prijs-berekenen/{typeId}/stap-3", requirements={
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

            return $this->redirectToRoute('price_calculator_step_one_' . $request->getLocale(), $params, 301);
        }

        return false;
    }
}