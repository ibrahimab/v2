<?php
namespace AppBundle\Service\Api\Accommodation;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * AccommodationServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface AccommodationServiceRepositoryInterface
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
     * This method selects all the accommodations
     *
     * @param  array $options
     * @return AcommodationServiceEntityInterface[]
     */
    public function all($options  = []);
    
    /**
     * Select a single accommodation with a flag (be it any field the accommodation has)
     *
     * @param  array $by
     * @return AcommodationServiceEntityInterface|null
     */
    public function find($by = []);
}