<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

/**
 * SurveyServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface SurveyServiceRepositoryInterface
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
     * @param TypeServiceEntityInterface $type
     * @return array
     */
    public function statsByType(TypeServiceEntityInterface $type);
    
    /**
     * @param TypeServiceEntityInterface[] $types
     * @return array
     */
    public function statsByTypes($types);
    
    /**
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function statsByPlace(PlaceServiceEntityInterface $place);
    
    /**
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function statsByRegion(RegionServiceEntityInterface $region);
    
    /**
     * @param CountryServiceEntityInterface $country
     * @return array
     */
    public function statsByCountry(CountryServiceEntityInterface $country);
}