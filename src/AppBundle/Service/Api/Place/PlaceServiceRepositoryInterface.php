<?php
namespace AppBundle\Service\Api\Place;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;

/**
 * PlaceServiceRepositoryInterface
 *
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
    
    /**
     * Getting places flagged as 'homepage' place
     *
     * @param RegionServiceEntityInterface $region
     * @param array $options
     * @return PlaceServiceEntityInterface[]
     */
    public function findHomepagePlaces(RegionServiceEntityInterface $region, $options = []);
}