<?php
namespace AppBundle\Service\Api\Autocomplete;

/**
 * AutocompleteService Repository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
interface AutocompleteServiceRepositoryInterface
{
    /**
     * Get all the autocomplete results
     *
     * @return Array
     */
    public function all();
    
    /**
     * Search endpoint
     *
     * @param  string $term
     * @param  array  $kinds
     * @param  array  $options
     * @return TypeServiceEntityInterface[]|AccommodationServiceEntityInterface[]
     */
    public function search($term, $kinds, $options = []);
}