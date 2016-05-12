<?php
namespace AppBundle\Controller;

use AppBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * SurveyController
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class SurveyController extends Controller
{
    /**
     * @Route("/surveys", name="surveys", options={"expose": true})
     */
    public function show(Request $request)
    {
        $query         = $request->query;
        $surveyService = $this->get('app.api.booking.survey');
        $surveyData    = $surveyService->paginated($query->getInt('typeId'), $query->getInt('offset'), 5);

        $html = $this->render('partials/surveys.ajax.html.twig', ['surveys' => $surveyData['surveys']])
                     ->getContent();

        return new JsonResponse([

            'type'   => (count($surveyData['surveys']) > 0 ? 'success' : 'failed'),
            'html'   => $html,
            'offset' => $surveyData['offset'],
            'total'  => $surveyData['total'],
        ]);
    }
}