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
     * @return RegionServiceEntityInterface
     */
    public function find($by = []);
    
    /**
     * Find by locale name
     *
     * @param string $name
     * @param string $locale
     * @return RegionServiceEntityInterface
     */
    public function findByLocaleName($name, $locale);
}