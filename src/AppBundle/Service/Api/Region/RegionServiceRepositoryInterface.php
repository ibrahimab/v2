<?php
namespace AppBundle\Service\Api\Region;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface RegionServiceRepositoryInterface
{
    /**
     * Fetching regions
     *
     * Fetching all the places based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     */
    public function all($options = []);
    
    /**
     * Finding a single region
     *
     * @param array $by
     */
    public function find($by = []);
}