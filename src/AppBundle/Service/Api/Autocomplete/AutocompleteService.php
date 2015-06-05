<?php
namespace AppBundle\Service\Api\Autocomplete;

/**
 * AutocompleteService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class AutocompleteService
{
    const KIND_COUNTRY       = 'country';
    const KIND_REGION        = 'region';
    const KIND_PLACE         = 'place';
    const KIND_ACCOMMODATION = 'accommodation';
    const KIND_TYPE          = 'type';

    private $allowedKinds    = [
        self::KIND_COUNTRY, self::KIND_REGION, self::KIND_PLACE,
    ];

    /**
     * @var AutocompleteServiceRepositoryInterface
     */
    private $autocompleteServiceRepository;
    
    /**
     * @var array
     */
    private $results;
    
    /**
     * @var array
     */
    private $tree;
    
    /**
     * @var array
     */
    private $flattened;

    /**
     * Constructor
     *
     * @param AutocompleteServiceRepositoryInterface $autocompleteServiceRepository
     */
    public function __construct(AutocompleteServiceRepositoryInterface $autocompleteServiceRepository)
    {
        $this->autocompleteServiceRepository = $autocompleteServiceRepository;
        $this->results                       = [];
        $this->tree                          = [];
        $this->flattened                     = [];
    }
    
    /**
     * Get all the endpoints
     *
     * @return Array
     */
    public function all()
    {
        $this->results = $this->autocompleteServiceRepository->all();
        
        return $this;
    }

    /**
     * Search endpoint
     *
     * @param string $term
     * @param array  $kinds
     * @param array  $options
     * @return Array
     */
    public function search($term, $kinds, $options = [])
    {
        // array_diff should return 0 elements, so
        // casting it to boolean returns true if kinds are correct
        if (false === !(array_diff($kinds, $this->allowedKinds))) {
            throw new AutocompleteServiceException(vsprintf('%s are not supported, supported kinds: %s', [implode(',', $kinds), implode(',', $this->allowedKinds)]));
        }

        $this->results = $this->autocompleteServiceRepository->search($term, $kinds, $options);
        
        return $this;
    }
    
    /**
     * Parse results into tree format
     *
     * @param array $results
     * @return Array
     */
    public function parse()
    {
        $results        = $this->results;
        $countries      = array_column((isset($results[self::KIND_COUNTRY])       ? $results[self::KIND_COUNTRY]       : []), null, 'type_id');
        $regions        = array_column((isset($results[self::KIND_REGION])        ? $results[self::KIND_REGION]        : []), null, 'type_id');
        $places         = array_column((isset($results[self::KIND_PLACE])         ? $results[self::KIND_PLACE]         : []), null, 'type_id');

        foreach ($places as $placeId => $place) {
            
            if (isset($regions[$place['region_id']])) {
                
                if (!isset($regions[$place['region_id']]['children'])) {
                    $regions[$place['region_id']]['children'] = [];
                }
                
                $regions[$place['region_id']]['country_id'] = $place['country_id'];
                $regions[$place['region_id']]['children'][] = $place;
                unset($places[$place['type_id']]);
            }
            
            if (isset($countries[$place['country_id']])) {
                
                if (!isset($countries[$place['country_id']]['children'])) {
                    $countries[$place['country_id']]['children'] = [];
                }
                
                $countries[$place['country_id']]['children'][] = $place;
                unset($places[$place['type_id']]);
            }
        }
        
        foreach ($regions as $regionId => $region) {
            
            if (isset($region['country_id']) && isset($countries[$region['country_id']])) {
                
                if (!isset($countries[$region['country_id']]['children'])){
                    $countries[$region['country_id']]['children'] = [];
                }
                
                $countries[$region['country_id']]['children'][] = $region;
                unset($regions[$region['type_id']]);
            }
        }
        
        $this->tree = [self::KIND_COUNTRY => $countries, self::KIND_REGION => $regions, self::KIND_PLACE => $places];
                       
        return $this;
    }
    
    /**
     * Flatten tree for better readability
     *
     * @return Array
     */
    public function flatten()
    {
        $results = [];
        $flattenArray = function($tree, &$results) use (&$flattenArray) {
            
            foreach ($tree as $leaf) {
                
                $results[] = $leaf;
                
                if (isset($leaf['children'])) {
                    $flattenArray($leaf['children'], $results);
                }
            }
            
            return $results;
        };
        
        foreach ($this->tree as $kind => $leaf) {
            $flattenArray($leaf, $results);
        }
        
        $this->flattened = $results;
        
        return $this;
    }
    
    /**
     * Return raw results
     *
     * @return Array
     */
    public function results()
    {
        return $this->results;
    }
    
    /**
     * Return parsed results
     *
     * @return Array
     */
    public function tree()
    {
        return $this->tree;
    }
    
    /**
     * Return flattened results
     *
     * @return Array
     */
    public function flattened()
    {
        return $this->flattened;
    }
}