<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

interface SurveyServiceRepositoryInterface
{
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