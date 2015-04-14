<?php
namespace AppBundle\Service\Api\Place;

use       AppBundle\Concern\SeasonConcern;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface PlaceServiceRepositoryInterface
{
    /**
     * Setting season
     * 
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern);
    
    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason();
    /**
     * Fetching places
     *
     * Fetching all the places based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return PlaceServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Finding a single place
     *
     * @param array $by
     * @return PlaceServiceEntityInterface
     */
    public function find($by = []);
    
    /**
     * Find by locale seo name
     *
     * @param string $seoName
     * @param string $locale
     * @return PlaceServiceEntityInterface
     */
    public function findByLocaleSeoName($seoName, $locale);
}