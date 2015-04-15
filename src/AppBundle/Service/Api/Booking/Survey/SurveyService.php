<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

class SurveyService
{
    /**
     * @var SurveyServiceRepositoryInterface
     */
    public $surveyRepository;
    
    /**
     * Constructor
     * 
     * @param SurveyServiceRepositoryInterface $surveyRepository
     */
    public function __construct(SurveyServiceRepositoryInterface $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }
    
    /**
     * Get single survey by some criteria
     *
     * @param array $by
     * @return SurveyServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->surveyRepository->find($by);
    }
    
    /**
     * Get all the surveys based on criteria passed in
     *
     * @param array options
     * @return SurveyServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->surveyRepository->all($options);
    }
    
    /**
     * Get all the surveys based on criteria passed in
     *
     * @param  TypeServiceEntityInterface $type
     * @return SurveyServiceEntityInterface[]
     */
    public function allByType(TypeServiceEntityInterface $type)
    {
        return $this->surveyRepository->allByType($type);
    }
    
    /**
     * @param TypeServiceEntityInterface $type
     * @return array
     */
    public function statsByType(TypeServiceEntityInterface $type)
    {
        return $this->surveyRepository->statsByType($type);
    }
    
    /**
     * @param TypeServiceEntityInterfaces[] $types
     * @return array
     */
    public function statsByTypes($types)
    {
        return $this->surveyRepository->statsByTypes($types);
    }
    
    /**
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function statsByPlace(PlaceServiceEntityInterface $place)
    {
        return $this->surveyRepository->statsByPlace($place);
    }
    
    /**
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function statsByRegion(RegionServiceEntityInterface $region)
    {
        return $this->surveyRepository->statsByRegion($region);
    }
    
    /**
     * @param CountryServiceEntityInterface $country
     * @return array
     */
    public function statsByCountry(CountryServiceEntityInterface $country)
    {
        return $this->surveyRepository->statsByCountry($country);
    }
}