<?php
namespace AppBundle\Service\Api\Country;

use       AppBundle\Concern\SeasonConcern;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface CountryServiceRepositoryInterface
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
     * Fetching countries
     *
     * Fetching all the places based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return CountryServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Finding a single country
     *
     * @param array $by
     * @return CountryServiceEntityInterface
     */
    public function find($by = []);
    
    /**
     * Finding a single country by its locale name
     *
     * @param string $name
     * @param string $locale
     * @param string $sort
     * @return CountryServiceEntityInterface
     */
    public function findByLocaleName($name, $locale, $sort = 'alpha');
}