<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;
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
     * @Template(":types:show.html.twig")
     */
    public function showAction($beginCode, $typeId)
    {
        $typeService    = $this->get('app.api.type');
        $surveyService  = $this->get('app.api.booking.survey');
        $season         = $this->get('app.concern.season');
        $featureService = $this->get('old.feature');

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

            $features = $featureService->get_kenmerken($type->getId(), $data);

        } catch (\Exception $e) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found: (' . $e->getMessage() . ')');
        }

        $accommodationTypes = $accommodation->getTypes();
        $typeIds            = [];
        foreach ($accommodationTypes as $accommodationType) {
            $typeIds[] = $accommodationType->getId();
        }

        $pricesService = $this->get('old.prices.wrapper');
        $prices        = $pricesService->get($typeIds);

        $priceService  = $this->get('app.api.price');
        $offers        = $priceService->offers($typeIds);

        $userService   = $this->get('app.api.user');
        $userService->addViewedAccommodation($type);

        return [

            'type'               => $type,
            'surveyData'         => $surveyData,
            'minimalSurveyCount' => $this->container->getParameter('app')['minimalSurveyCount'],
            'features'           => array_keys($features),
            'prices'             => $prices,
            'offers'             => $offers,
        ];
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
     * @Route("/options")
     */
    public function options()
    {
        $typeService   = $this->get('app.api.type');
        $optionService = $this->get('app.api.option');

        $type          = $typeService->find(['id' => 240]);
        $options       = $optionService->options($type);

        return new Response();
    }
}