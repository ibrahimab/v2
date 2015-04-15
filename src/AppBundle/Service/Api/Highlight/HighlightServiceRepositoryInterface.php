<?php
namespace AppBundle\Service\Api\Highlight;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * HighlightServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface HighlightServiceRepositoryInterface
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
     * Get all highlights
     *
     * @param array $options
     * @return HighlightServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Get one highlight based on certain criteria
     *
     * @param  array $by
     * @return HighlightServiceEntityInterface|null
     */
    public function find($by = []);
}