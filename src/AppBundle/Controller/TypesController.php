<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Doctrine\ORM\NoResultException;

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
     * })
     * @Route(name="show_type_en", path="/accommodation/{beginCode}{typeId}", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Breadcrumb(name="show_country", title="{countryName}",       path="show_country", pathParams={"countrySlug"})
     * @Breadcrumb(name="show_region",  title="{regionName}",        path="show_region",  pathParams={"regionSlug"})
     * @Breadcrumb(name="show_place",   title="{placeName}",         path="show_place",   pathParams={"placeSlug"})
     * @Breadcrumb(name="show_type",    title="{accommodationName}", active=true)
     * @Template(":types:show.html.twig")
     */
    public function showAction($beginCode, $typeId)
    {
        $typeService    = $this->get('service.api.type');
        $surveyService  = $this->get('service.api.booking.survey');
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

        return [

            'type'               => $type,
            'surveyData'         => $surveyData,
            'minimalSurveyCount' => $this->container->getParameter('app')['minimalSurveyCount'],
            'features'           => array_keys($features),
        ];
    }
}