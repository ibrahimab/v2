<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\Response;
use       Symfony\Component\HttpFoundation\JsonResponse;

/**
 * TypesController
 *
 * This controller handles the accommodation page (which shows one type)
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class TypesController extends Controller
{
    /**
     * @Route(name="show_type_nl", path="/accommodatie/{beginCode}{typeId}", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * }, options={"expose": true})
     * @Route(name="show_type_en", path="/accommodation/{beginCode}{typeId}", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * }, options={"expose": true})
     * @Breadcrumb(name="show_country", title="{countryName}",       path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",  title="{regionName}",        path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",   title="{placeName}",         path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",    title="{accommodationName}", active=true)
     */
    public function showAction($beginCode, $typeId, Request $request)
    {
        $typeService    = $this->get('app.api.type');
        $surveyService  = $this->get('app.api.booking.survey');
        $season         = $this->get('app.concern.season');
        $featureService = $this->get('app.api.legacy.features');

        try {

            $type          = $typeService->findById($typeId);
            $surveyData    = $surveyService->allByType($type);
            $accommodation = $type->getAccommodation();
            $place         = $accommodation->getPlace();
            $region        = $place->getRegion();
            $data          = [

                'type'         => implode(',', $type->getFeatures()),
                'accommodatie' => implode(',', $accommodation->getFeatures()),
                'plaats'       => implode(',', $place->getFeatures()),
                'skigebied'    => implode(',', $region->getFeatures()),
                'toonper'      => $accommodation->getShow(),
            ];

            $features = $featureService->all($type->getId(), $data);

            $accommodationTypes = $accommodation->getTypes();
            $typeIds            = [];
            foreach ($accommodationTypes as $accommodationType) {
                $typeIds[] = $accommodationType->getId();
            }

            $startingPrice     = $this->get('app.api.legacy.starting_price');
            $prices            = $startingPrice->getStartingPrices($typeIds);

            $priceService      = $this->get('app.api.price');
            $offers            = $priceService->offers($typeIds);

            $userService       = $this->get('app.api.user');
            $userService->addViewedAccommodation($type);

            $seasonService     = $this->get('app.api.season');
            $seasons           = $seasonService->seasons();
            $currentSeason     = $seasonService->current();
            $seasonId          = $currentSeason['id'];

            $priceTableService = $this->get('app.api.legacy.price_table');
            $priceTable        = $priceTableService->getTable($typeId, $seasonId);

            $optionService     = $this->get('app.api.option');
            $options           = $optionService->options($type->getAccommodationId(), $seasonId, $request->query->get('w', null));

            if (false == ($backUrl = $this->getBackUrl($request->query->get('back', '')))) {
                $backUrl = '';
            }

            return $this->render('types/show.html.twig', [

                'type'               => $type,
                'surveyData'         => $surveyData,
                'minimalSurveyCount' => $this->container->getParameter('app')['minimalSurveyCount'],
                'features'           => array_keys($features),
                'prices'             => $prices,
                'offers'             => $offers,
                'options'            => $options,
                'weekends'           => $seasonService->futureWeekends($seasons),
                'sunnyCars'          => $this->container->getParameter('sunny_cars'),
                'currentWeekend'     => $request->query->get('w', null),
                'currentSeason'      => $currentSeason,
                'priceTable'         => $priceTable['html'],
                'back_url'           => $backUrl,
            ]);

        } catch (NoResultException $e) {

            $response = $this->render('types/not-found.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }
    }

    /**
     * @Route("/types/save/{typeId}", name="save_favorite", options={"expose": true})
     */
    public function save($typeId)
    {
        try {

            $typeService = $this->get('app.api.type');
            $userService = $this->get('app.api.user');
            $type        = $typeService->findById($typeId);

            $userService->addFavoriteAccommodation($type);

            return new JsonResponse([

                'type'    => 'success',
                'message' => 'Saved type',
            ]);

        } catch (NoResultException $exception) {

            return new JsonResponse([

                'type'    => 'error',
                'message' => 'Could not save type',
            ]);
        }
    }

    /**
     * @Route("/types/remove/{typeId}", name="remove_favorite", options={"expose": true})
     */
    public function remove($typeId)
    {
        try {

            $typeService = $this->get('app.api.type');
            $userService = $this->get('app.api.user');
            $type        = $typeService->findById($typeId);

            $userService->removeFavoriteAccommodation($type);

            return new JsonResponse([

                'type'    => 'success',
                'message' => 'Removed type',
            ]);

        } catch (NoResultException $exception) {

            return new JsonResponse([

                'type'    => 'error',
                'message' => 'Could not remove type',
            ]);
        }
    }

    /**
     * @Route(path="/types/totalprice/{typeId}/{seasonIdInquery}/{date}/{numberOfPersons}", name="type_total_price", options={"expose": true})
     */
    public function totalPrice($typeId, $seasonIdInquery, $date, $numberOfPersons)
    {
        $priceTableService = $this->get('app.api.legacy.price_table');
        $priceTable        = $priceTableService->getTotalPrice($typeId, $seasonIdInquery, $date, $numberOfPersons);

        return new JsonResponse([

            'type'    => 'success',
            'html'    => $priceTable['html'],
        ]);
    }

    /**
     * @param string $url
     *
     * @return string|boolean
     */
    public function getBackUrl($url)
    {
        $parsed = parse_url($url);

        if (!isset($parsed['host'])) {
            return false;
        }

        if (!isset($parsed['path'])) {
            return false;
        }

        if (!isset($parsed['scheme'])) {
            $parsed['scheme'] = ($this->getParameter('ssl_enabled') ? 'https' : 'http');
        }

        $parsed['query'] = (isset($parsed['query']) ? ('?' . urlencode($parsed['query'])) : '');

        return $parsed['scheme'] . '://' . $parsed['host'] . $parsed['path'] . $parsed['query'];
    }
}
