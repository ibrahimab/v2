<?php
namespace AppBundle\Tests\Unit\Service\Api\Booking;


class SurveyServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var \appTestDebugProjectContainer
     */
    protected $serviceContainer;
    
    /**
     * @var \AppBundle\Service\Api\TypeService
     */
    protected $typeService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->surveyService    = $this->serviceContainer->get('service.api.booking.survey');
        $this->typeService      = $this->serviceContainer->get('service.api.type');
        $this->countryService   = $this->serviceContainer->get('service.api.country');
        $this->regionService    = $this->serviceContainer->get('service.api.region');
        $this->placeService     = $this->serviceContainer->get('service.api.place');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->surveyService  = null;
        $this->typeService    = null;
        $this->countryService = null;
        $this->regionService  = null;
        $this->placeService   = null;
    }
    
    public function testGetSurveys()
    {
        // get surveys
        $limit   = 3;
        $surveys = $this->surveyService->all(['limit' => $limit]);
        
        $this->assertCount($limit, $surveys);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $surveys);
    }
    
    public function testGetSurvey()
    {
        // get survey
        $survey = $this->surveyService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $survey);
    }

    public function testGetSurveysByType()
    {
        // Get type
        $type  = $this->typeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        
        // get surveys
        $surveys = $this->surveyService->allByType($type);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $surveys);
    }
    
    public function testCountSurveysSingleType()
    {
        $type = $this->typeService->find(['id' => 1]);
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        
        $surveyStats = $this->surveyService->statsByType($type);
        $this->assertArrayHasKey('surveyCount', $surveyStats);
    }
    
    public function testGetStatsSurveysMultipleTypes()
    {
        $types = $this->typeService->all(['limit' => 3]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $types);
        
        $surveyStats = $this->surveyService->statsByTypes($types);
        $this->assertInternalType('array', $surveyStats);

        foreach ($surveyStats as $surveyStat) {
            $this->assertEquals(1, intval($surveyStat['surveyCount']));
        }
    }
    
    public function testCountSurveysSingleCountry()
    {
        $country = $this->countryService->find(['id' => 1]);
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);
        
        $surveyStats = $this->surveyService->statsByCountry($country);
        $this->assertArrayHasKey('surveyCount', $surveyStats);
    }
    
    public function testCountSurveysSingleRegion()
    {
        $region = $this->regionService->find(['id' => 1]);
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $region);
        
        $surveyStats = $this->surveyService->statsByRegion($region);
        $this->assertArrayHasKey('surveyCount', $surveyStats);
    }
    
    public function testCountSurveysSinglePlace()
    {
        $place = $this->placeService->find(['id' => 1]);
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
        
        $surveyStats = $this->surveyService->statsByPlace($place);
        $this->assertArrayHasKey('surveyCount', current($surveyStats));
    }
    
    public function testGetOverallRatingSurvey()
    {
        $survey = $this->surveyService->find(['question_1_7' => 1]);
        
        $this->assertInstanceOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $survey);
        $this->assertEquals(1, $survey->getOverallRating());
    }
    
    public function testGetAverageOverallRatingType()
    {
        // first getting type
        $type = $this->typeService->find(['id' => 1]);
        
        // then get overall rating
        $surveyStats = $this->surveyService->statsByType($type);
        $this->assertArrayHasKey('surveyAverageOverallRating', $surveyStats);
    }
}