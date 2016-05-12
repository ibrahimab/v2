<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\UtilsService;
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
     * @Route(name="type_redirect_1", path="/{countryCode}{typeId}{trailingSlash}", requirements={
     *    "countryCode": "[A-Za-z]{1,2}",
     *    "typeId": "\d+",
     *    "trailingSlash" : "[/]{0,1}"
     * }, defaults={"trailingSlash" = "/"})
     * @Route(name="type_redirect_2", path="/accommodatie/{countryCode}{typeId}{trailingSlash}", requirements={
     *    "countryCode": "[A-Za-z]{1,2}",
     *    "typeId": "\d+",
     *    "trailingSlash" : "[/]{0,1}"
     * }, defaults={"trailingSlash" = "/"})
    * @Route(name="type_redirect_3", path="/accommodatie/{countryCode}{typeId}/{fullAccName}", requirements={
    *    "countryCode": "[A-Za-z]{1,2}",
    *    "typeId": "\d+",
    *    "trailingSlash" : "[/]{0,1}"
    * }, defaults={"fullAccName" = ".+"})
    * @Route(name="type_redirect_4", path="/wintersport/{countryCode}{typeId}{trailingSlash}", requirements={
    *    "countryCode": "[A-Za-z]{1,2}",
    *    "typeId": "\d+",
    *    "trailingSlash" : "[/]{0,1}"
    * }, defaults={"trailingSlash" = "/"})
    */
    public function redirectAction($countryCode, $typeId, Request $request)
    {

        $typeService = $this->get('app.api.type');

        try {
            $type = $typeService->findById($typeId);
        } catch (NoResultException $e) {

            $response = $this->render('types/not-found.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;

        }

        // @todo: translate this! (winter-sports for English sites)
        $url = '/wintersport';

        $url .= '/' . strtolower($type->getAccommodation()->getPlace()->getCountry()->getCountryCode()) . $typeId;

        $fullAccName = ucfirst($this->get('translator')->trans('type.kind.' . $type->getAccommodation()->getKindIdentifier()));
        $fullAccName .= ' ' . $type->getAccommodation()->getName();
        if (null !== $type->getName()) {
            $fullAccName .= ' ' . $type->getName();
        }
        $url .= '/' . UtilsService::seo($fullAccName);

        // check for query string
        $qs = $request->server->get('QUERY_STRING');
        if ($qs) {
            // add query string
            $url .= '?' . $qs;
        }

        return $this->redirect($url, 301);

    }

    /**
     * @Route(name="show_type_nl", path="/wintersport/{countryCode}{typeId}/{fullAccName}", requirements={
     *    "countryCode": "[A-Za-z]{1,2}",
     *    "typeId": "\d+",
     *    "fullAccname": ".+"
     * }, options={"expose": true}, defaults={"fullAccName" = null})
     * @Route(name="show_type_en", path="/winter-sports/{countryCode}{typeId}/{fullAccName}", requirements={
     *    "countryCode": "[A-Za-z]{1,2}",
     *    "typeId": "\d+",
     *    "fullAccname": ".+"
     * }, options={"expose": true}, defaults={"fullAccName" = null})
     * @Breadcrumb(name="show_country", title="{countryName}",       path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",  title="{regionName}",        path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",   title="{placeName}",         path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",    title="{accommodationName}", active=true)
     */
    public function showAction($countryCode, $typeId, $fullAccName, Request $request)
    {
        $typeService            = $this->get('app.api.type');
        $surveyService          = $this->get('app.api.booking.survey');
        $season                 = $this->get('app.concern.season');
        $featureService         = $this->get('app.api.legacy.features');
        $legacyCmsUserService   = $this->get('app.legacy.cmsuser');
        $generalSettingsService = $this->get('app.api.general.settings');

        try {

            $type = $typeService->findById($typeId);

            if ($type->getDisplay() === false || $type->getAccommodation()->getDisplay() === false) {

                if ($type->getRedirectToType() !== null) {

                    $full           = $type->getRedirectToType();
                    $countryCode      = substr($full, 0, 1);
                    $redirectTypeId = substr($full, 1);

                    $response = $this->redirectToRoute('show_type_' . $this->get('app.concern.locale')->get(), ['countryCode' => $countryCode, 'typeId' => $redirectTypeId]);
                    $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);

                    return $response;
                }

                throw new NoResultException('Type was found but display is turned off');
            }

            $surveyData    = $surveyService->paginated($type->getId(), 0, 3);
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

            $pricesAndOffersService = $this->get('app.api.prices_and_offers');
            $params = $pricesAndOffersService->createParamsFromRequest($request);

            $offers = $pricesAndOffersService->getOffers($typeIds);
            $prices = $pricesAndOffersService->getPrices($typeIds, $params);

            $userService = $this->get('app.api.user');
            $userService->addViewedAccommodation($type);

            $seasonService        = $this->get('app.api.season');
            $seasons              = $seasonService->seasons();
            $currentSeason        = $seasonService->current();
            $seasonId             = $currentSeason['id'];

            $priceTableService    = $this->get('app.api.legacy.price_table');
            $priceTable           = $priceTableService->getTable($typeId, $seasonId, $request->query->get('w', null), $request->query->get('pe', null), $request->getUri());

            $optionService        = $this->get('app.api.option');
            $options              = $optionService->options($type->getAccommodationId(), $seasonId, $request->query->get('w', null));

            $date                 = $params->getWeekend() ?: null;
            $numberOfPersons      = $params->getPersons() ?: null;

            $surveyDataOtherTypes = array_column($surveyService->statsByTypes($typeIds), null, 'typeId');

            if (false == ($backUrl = $this->getBackUrl($request->query->get('back', '')))) {
                $backUrl = '';
            }

            $internalInfo = [
                'showLinkWithoutInternalInfo' => false,
                'showInternalInfoSlider'      => false,
            ];

            if ($legacyCmsUserService->shouldShowInternalInfo()) {

                // link to CMS accommodation
                $internalInfo['cmsLinks'][] = [
                    'url'          => '/cms_accommodaties.php?show=1&wzt=' . $accommodation->getSeason() . '&1k0=' . $accommodation->getId(),
                    'name'         => 'accommodatie bewerken',
                    'target_blank' => true
                ];

                // link to CMS type
                $internalInfo['cmsLinks'][] = [
                    'url'          => '/cms_types.php?show=2&wzt=' . $accommodation->getSeason() . '&2k0=' . $type->getId(),
                    'name'         => 'type bewerken',
                    'target_blank' => true
                ];

                $internalInfo['showLinkWithoutInternalInfo'] = true;

                // show/hide slider with internal info
                if ($request->cookies->get('cms_info_slider') === 'visible' ) {
                    $internalInfo['showInternalInfoSlider'] = true;
                }

                $internalInfo['code'] = $countryCode . $type->getId() . ($type->getCode() ? ' - ' . $type->getCode() : '');
                if (true === $type->getIsCollectionType()) {
                    $internalInfo['code'] .= ' - verzameltype';
                }

                $internalInfo['supplier']['name']                  = $type->getSupplier()->getName();
                $internalInfo['supplier']['cms_url']               = '/cms_leveranciers.php?edit=8&back_to_show=1&8k0=' . $type->getSupplier()->getId();

                $internalInfo['supplier']['url']['type']           = $type->getSupplierUrl();
                $internalInfo['supplier']['url']['accommodation']  = $type->getAccommodation()->getSupplierUrl();
                $internalInfo['supplier']['url']['supplier']       = $type->getSupplier()->getUrl();

                $internalInfo['internalComments']['supplier']      = $type->getSupplier()->getInternalComments();
                $internalInfo['internalComments']['accommodation'] = $type->getAccommodation()->getInternalComments();
                $internalInfo['internalComments']['type']          = $type->getInternalComments();

                $internalInfo['surveyData']                        = $surveyService->allByType($type);

                $internalInfo['features']                          = $featureService->allBackEnd($type->getId(), $data);

            }

            return $this->render('types/show.html.twig', [

                'type'                        => $type,
                'surveyData'                  => $surveyData,
                'surveyDataOtherTypes'        => $surveyDataOtherTypes,
                'minimalSurveyCount'          => $this->container->getParameter('app')['minimalSurveyCount'],
                'features'                    => array_keys($features),
                'prices'                      => $prices,
                'offers'                      => $offers,
                'options'                     => $options,
                'weekends'                    => $seasonService->futureWeekends($seasons),
                'sunnyCars'                   => $this->container->getParameter('sunny_cars'),
                'currentWeekend'              => $request->query->get('w', null),
                'currentSeason'               => $currentSeason,
                'priceTable'                  => $priceTable['html'],
                'back_url'                    => $backUrl,
                'date'                        => $date,
                'numberOfPersons'             => $numberOfPersons,
                'internalInfo'                => $internalInfo,
                'cmsLinks'                    => $cmsLinks,
                'showLinkWithoutInternalInfo' => $showLinkWithoutInternalInfo,
                'noPriceShowUnavailable'      => $generalSettingsService->getNoPriceShowUnavailable(),

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

        if (!isset($parsed['path'])) {
            return false;
        }

        $parsed['query'] = (isset($parsed['query']) ? ('?' . $parsed['query']) : '');

        return $parsed['path'] . $parsed['query'];
    }
}
