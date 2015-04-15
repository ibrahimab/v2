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
     * @Template(":Types:show.html.twig")
     */
    public function showAction($beginCode, $typeId)
    {
        $typeService   = $this->get('service.api.type');
        $surveyService = $this->get('service.api.booking.survey');
        
        try {
            
            $type = $typeService->findById($typeId);
            
        } catch (NoResultException $e) {
            throw $this->createNotFoundException('Type with code=' . $typeId . ' could not be found');
        }
        
        $surveyData = $surveyService->allByType($type);
        // $this->get('translator')->trans('catering');
        // $this->get('translator')->trans('ski-run');
        // $this->get('translator')->trans('sauna');
        // $this->get('translator')->trans('sauna-private');
        // $this->get('translator')->trans('swimming-pool');
        // $this->get('translator')->trans('swimming-pool-private');
        // $this->get('translator')->trans('child-friendly');
        // $this->get('translator')->trans('chalet-for-two');
        // $this->get('translator')->trans('large-groups');
        // $this->get('translator')->trans('price-conscious');
        // $this->get('translator')->trans('top-selection');
        // $this->get('translator')->trans('winter-wellness');
        // $this->get('translator')->trans('fireplace');
        // $this->get('translator')->trans('pets-allowed');
        // $this->get('translator')->trans('allergy-free');
        // $this->get('translator')->trans('rent-sunday');
        // $this->get('translator')->trans('chalet-for-two');
        // $this->get('translator')->trans('special');
        // $this->get('translator')->trans('waching-machine');
        // $this->get('translator')->trans('balcony');
        // $this->get('translator')->trans('balcony-terrace');
        // $this->get('translator')->trans('pets-disallowed');
        // $this->get('translator')->trans('internet');
        // $this->get('translator')->trans('charming-chalet');
        // $this->get('translator')->trans('internet-wifi');
        // $this->get('translator')->trans('jacuzzi');
        return [
            
            'type'       => $type,
            'surveyData' => $surveyData,
        ];
    }
}