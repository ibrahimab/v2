<?php
namespace AppBundle\Service\Api\Place;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface PlaceServiceRepositoryInterface
{
    /**
     * Fetching places
     *
     * Fetching all the places based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     */
    public function all($options = []);
    
    /**
     * Finding a single place
     *
     * @param array $by
     */
    public function find($by = []);
}