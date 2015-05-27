<?php
namespace AppBundle\Service\Api\Region;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * RegionServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface RegionServiceRepositoryInterface
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
     * Setting website
     * 
     * @param WebsiteConcern $seasonConcern
     * @return void
     */
    public function setWebsite(WebsiteConcern $websiteConcern);
    
    /**
     * Getting website
     *
     * @return integer
     */
    public function getWebsite();
    
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
    
    /**
     * Find by locale name
     *
     * @param string $name
     * @param string $locale
     * @return RegionServiceEntityInterface
     */
    public function findByLocaleSeoName($seoName, $locale);
    
    /**
     * Find random regions flagged as 'homepage' region
     *
     * @param array $options
     * @return RegionServiceEntityInterface
     */
    public function findHomepageRegions($options = []);
}