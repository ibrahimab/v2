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
        $kinds               = [AutocompleteService::KIND_COUNTRY, AutocompleteService::KIND_REGION, AutocompleteService::KIND_PLACE, AutocompleteService::KIND_ACCOMMODATION];

        $autocompleteService->search($term, $kinds)->limit($limit)->parse()->flatten();

        $results     = $autocompleteService->flattened();
        $searchables = [];
        $locale      = $this->get('app.concern.locale')->get();

        foreach ($results as $result) {

            $key = $result['searchable'];

            if (is_array($result['searchable'])) {
                $key = (isset($result['searchable'][$locale]) ? $result['searchable'][$locale] : '');
            }

            $searchables[$key][] = $result;
        }

        $response = [];

        // grouping multiple searches to make it a freesearch
        foreach ($searchables as $searchable => $records) {

            if (count($records) > 1) {
                $records[0]['type'] = 'freesearch';
            }

            $response[] = $records[0];
        }

        return new JsonResponse(array_slice($response, 0, $limit));
    }
}