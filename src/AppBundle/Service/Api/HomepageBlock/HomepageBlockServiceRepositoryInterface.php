<?php
namespace AppBundle\Service\Api\HomepageBlock;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * HomepageBlockServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface HomepageBlockServiceRepositoryInterface
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
     * Fetching homepage blocks
     *
     * Fetching all the homepage blocks based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Finding a single homepage blocks
     *
     * @param array $by
     * @return HomepageBlockServiceEntityInterface
     */
    public function find($by = []);
    
    /**
     * Finding published blocks
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function published($options = []);
}