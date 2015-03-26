<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
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
     * @param CountryServiceEntityInterface $type
     * @return array
     */
    public function statsByCountry(CountryServiceEntityInterface $country);
}