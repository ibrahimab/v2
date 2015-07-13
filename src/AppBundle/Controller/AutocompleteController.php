<?php
namespace AppBundle\Controller;
use       AppBundle\Service\Api\Autocomplete\AutocompleteService;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\JsonResponse;

/**
 * AutocompleteController
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class AutocompleteController extends Controller
{
    /**
     * @Route("/autocomplete/{term}/{limit}", name="autocomplete", defaults={"limit": 5}, options={"expose": true})
     */
    public function search($term, $limit)
    {
        $autocompleteService = $this->get('app.api.autocomplete');
        $kinds               = [AutocompleteService::KIND_COUNTRY, AutocompleteService::KIND_REGION, AutocompleteService::KIND_PLACE, AutocompleteService::KIND_TYPE];

        $autocompleteService->search($term, $kinds)->limit($limit)->parse()->flatten();

        return new JsonResponse(array_slice($autocompleteService->flattened(), 0, $limit));
    }
}