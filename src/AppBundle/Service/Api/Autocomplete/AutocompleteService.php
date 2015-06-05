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
        self::KIND_ACCOMMODATION, self::KIND_TYPE,
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
     * Constructor
     *
     * @param AutocompleteServiceRepositoryInterface $autocompleteServiceRepository
     */
    public function __construct(AutocompleteServiceRepositoryInterface $autocompleteServiceRepository)
    {
        $this->autocompleteServiceRepository = $autocompleteServiceRepository;
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

        return $this->autocompleteServiceRepository->search($term, $kinds, $options);
    }
    
    /**
     * Parse results into tree format
     *
     * @param array $results
     * @return Array
     */
    public function tree()
    {
        $results        = $this->results;
        $countries      = array_column((isset($results[self::KIND_COUNTRY])       ? $results[self::KIND_COUNTRY]       : []), null, 'type_id');
        $regions        = array_column((isset($results[self::KIND_REGION])        ? $results[self::KIND_REGION]        : []), null, 'type_id');
        $places         = array_column((isset($results[self::KIND_PLACE])         ? $results[self::KIND_PLACE]         : []), null, 'type_id');
        $accommodations = array_column((isset($results[self::KIND_ACCOMMODATION]) ? $results[self::KIND_ACCOMMODATION] : []), null, 'type_id');
        $types          = array_column((isset($results[self::KIND_TYPE])          ? $results[self::KIND_TYPE]          : []), null, 'type_id');
        
        $tree           = [];
        
        dump($countries, $results, $places, $accommodations, $types);exit;
    }
}