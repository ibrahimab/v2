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
    const KIND_ACCOMMODATION = 'accommodation';
    const KIND_TYPE          = 'type';
    
    private $allowedKinds    = [
        self::KIND_ACCOMMODATION, self::KIND_TYPE,
    ];
    
    /**
     * @var AutocompleteServiceRepositoryInterface
     */
    private $autocompleteServiceRepository;
    
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
     * Search endpoint
     * 
     * @param string $term
     * @param array  $kinds
     * @param array  $options
     * @return TypeServiceEntityInterface[]|AccommodationServiceEntityInterface[]
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
}