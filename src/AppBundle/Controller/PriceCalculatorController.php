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
use       IntlDateFormatter;

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

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type);

        $formService       = $calculatorService->getFormService();
        $form              = $formService->create(FormService::FORM_STEP_ONE);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $accommodationService = $this->get('app.api.legacy.accommodation');
            $seasonService        = $this->get('app.api.season');
            $currentSeason        = $seasonService->current();
            $data                 = $form->getData();
            $typeData             = $accommodationService->getInfo($type->getId(), $data->weekend, $data->person);

            $booking = new BookingEntity();
            $booking->setCalc(1)
                    ->setTypeId($typeData['typeid'])
                    ->setSeasonId($currentSeason['id'])
                    ->setPersons($data->person)
                    ->setArrivalAt($data->weekend);

            $bookingService = $this->get('app.booking');
            $bookingId      = $bookingService->setBooking($booking)
                                             ->create($typeData);

        } else {

            return $this->redirectToRoute('price_calculator_step_one_' . $this->get('app.concern.locale')->get(), [
                'typeId' => $typeData['typeid'],
            ]);
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson((int)$request->request->get('step_one')['person'])
                          ->setWeekend((int)$request->request->get('step_one')['weekend'])
                          ->setBookingId($bookingId);

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
     * @Breadcrumb(name="calculate_price", title="calculate-price-title",  translate=true,      active=true)
     */
    public function stepThree(Request $request, $typeId)
    {
        $type = $this->get('app.api.type')->findById($typeId);

        if (null === $type) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson((int)$request->request->get('step_two')['person'])
                          ->setWeekend((int)$request->request->get('step_two')['weekend'])
                          ->setBookingId((int)$request->request->get('step_two')['booking'])
                          ->setOptionsAmount($request->request->get('step_two')['options'])
                          ->setCancellationInsurancesAmount($request->request->get('step_two')['cancellation_insurances']);

        $formService       = $calculatorService->getFormService();
        $form              = $formService->create(FormService::FORM_STEP_TWO);
        $form->handleRequest($request);

        $data           = $form->getData();
        $insurances     = [
            'damage' => $data->damage_insurance,
        ];

        $bookingService = $this->get('app.booking');
        $bookingService->saveOptions($data->booking, $insurances, $data->persons, $form->getData()->options);

        $accommodationService = $this->get('app.api.legacy.accommodation');
        $typeData             = $accommodationService->getInfo($typeId, $data->weekend, $data->person);

        $formatter            = new IntlDateFormatter($request->getLocale(), IntlDateFormatter::FULL, IntlDateFormatter::FULL, new \DateTimeZone(date_default_timezone_get()), IntlDateFormatter::GREGORIAN);
        $formatter->setPattern('eeee dd MMMM y');

        $arrivalDate          = new \DateTime();
        $arrivalDate->setTimestamp($calculatorService->getWeekend());

        $departureDate        = new \DateTime();
        $departureDate->setTimestamp($typeData['vertrekdatum']);

        $travelsum = $this->get('app.api.legacy.travelsum');

        return $this->render('price_calculator/step_three.html.twig', [

            'type'              => $type,
            'type_data'         => $typeData,
            'show'              => $typeData['toonper'],
            'price'             => $typeData['tarief'],
            'name_type'         => $typeData['naam_ap'],
            'name_place'        => $typeData['plaats'] . ', ' . $typeData['land'],
            'persons'           => $data->person,
            'arrival_date'      => $formatter->format($arrivalDate),
            'departure_date'    => $formatter->format($departureDate),
            'reservation_costs' => $this->getParameter('app')['reservation_costs'],
            'options'           => $data->options,
            'travelsum_table'   => $travelsum->table($data->booking, $type->getId(), $calculatorService->getWeekend(), $calculatorService->getPerson())['table'],
            'form'              => $calculatorService->getFormService()->create(FormService::FORM_STEP_THREE)->createView(),
            'form_data'         => $form->getData(),
        ]);
    }

    /**
     * @Route(name="price_calculator_step_four_nl", path="/prijs-berekenen/{typeId}/stap-4", requirements={
     *     "typeId": "\d+"
     * })
     * @Method("POST")
     * @Breadcrumb(name="show_country",    title="{countryName}",          path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",     title="{regionName}",           path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",      title="{placeName}",            path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",       title="{accommodationName}",    path="show_type",    pathParams={"beginCode", "typeId"})
     * @Breadcrumb(name="calculate_price", title="calculate-price-title",  translate=true,      active=true)
     */
    public function stepFour(Request $request, $typeId)
    {
        $type = $this->get('app.api.type')->findById($typeId);

        if (null === $type) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }

        $calculatorService = $this->get('app.price_calculator.calculator');
        $calculatorService->setType($type)
                          ->setPerson((int)$request->request->get('step_three')['person'])
                          ->setWeekend((int)$request->request->get('step_three')['weekend'])
                          ->setBookingId((int)$request->request->get('step_three')['booking'])
                          ->setOptionsAmount($request->request->get('step_three')['options'])
                          ->setCancellationInsurancesAmount($request->request->get('step_three')['cancellation_insurances']);

        $form = $calculatorService->getFormService()->create(FormService::FORM_STEP_THREE);
        $form->handleRequest($request);
        $data = $form->getData();

        $accommodationService = $this->get('app.api.legacy.accommodation');
        $typeData             = $accommodationService->getInfo($typeId, $data->weekend, $data->person);

        $formatter            = new IntlDateFormatter($request->getLocale(), IntlDateFormatter::FULL, IntlDateFormatter::FULL, new \DateTimeZone(date_default_timezone_get()), IntlDateFormatter::GREGORIAN);
        $formatter->setPattern('eeee dd MMMM y');

        $arrivalDate          = new \DateTime();
        $arrivalDate->setTimestamp($data->weekend);

        $departureDate        = new \DateTime();
        $departureDate->setTimestamp($typeData['vertrekdatum']);

        $travelsum = $this->get('app.api.legacy.travelsum');

        $table                = $this->renderView('price_calculator/table.html.twig', [

            'type_id'           => $typeData['typeid'],
            'begin_code'        => $typeData['begincode'],
            'arrival_date'      => $formatter->format($arrivalDate),
            'departure_date'    => $formatter->format($departureDate),
            'name_type'         => $typeData['naam_ap'],
            'name_place'        => $typeData['plaats'],
            'show'              => $typeData['toonper'],
            'price'             => $typeData['tarief'],
            'persons'           => $data->person,
            'reservation_costs' => $this->getParameter('app')['reservation_costs'],
            'options'           => $data->options,
            'travelsum_table'   => $travelsum->table($data->booking, $typeId, $data->weekend, $data->person)['table'],
        ]);

        $mailer = $this->get('app.mailer.price_calculator');
        $result = $mailer->setSubject($this->get('translator')->trans('mail.price_calculator.subject'))
                         ->setFrom($this->container->getParameter('mailer_from'))
                         ->setTo($data->email)
                         ->setTemplate('mail/price_calculator.html.twig', 'text/html')
                         ->setTemplate('mail/price_calculator.txt.twig', 'text/plain')
                         ->send([

                             'email' => $data->email,
                             'table' => $table,
                         ]);

        return new JsonResponse([

            'type'    => 'success',
            'message' => 'Message has successfully been sent',
        ]);
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