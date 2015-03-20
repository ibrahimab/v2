<?php
namespace Api;


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
        $this->surveyService    = $this->serviceContainer->get('service.api.survey');
        $this->typeService      = $this->serviceContainer->get('service.api.type');
    }
    
    protected function _after()
    {
        $this->surveyService = null;
        $this->typeService   = null;
    }

    public function testGetSurveys()
    {
        // Get type
        $type  = $this->typeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        
        // get surveys
        $surveys = $this->surveyService->all(['where' => ['type' => $type]]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $surveys);
    }
    
    public function testCountSurveysSingleType()
    {
        $type = $this->typeService->find(['id' => 1]);
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        
        $surveyStats = $this->surveyService->statsByType($type);
        
        foreach ($surveyStats as $surveyStat) {
            $this->assertEquals(1, $surveyStat['surveyCount']);
        }
    }
    
    public function testCountSurveysMultipleTypes()
    {
        $types = $this->typeService->all(['limit' => 3]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $types);
        
        $surveyStats = $this->surveyService->statsByTypes($types);
        $this->assertInternalType('array', $surveyStats);
        
        foreach ($surveyStats as $surveyStat) {
            $this->assertEquals(1, intval($surveyStat['surveyCount']));
        }
    }
    
    public function testGetOverallRatingSurvey()
    {
        $survey = $this->surveyService->find(['id' => 1]);
        
        $this->assertInstanceOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $survey, 'Check if there is a survey which has question_1_7 = 1, or else repopulate fixtures into test database');
        $this->assertEquals(1, $survey->getOverallRating());
    }
    
    public function testGetAverageOverallRatingType()
    {
        // first getting type
        $type = $this->typeService->find(['id' => 1]);
        
        // then get overall rating
        $surveyStats = $this->surveyService->statsByType($type);
        
        foreach ($surveyStats as $surveyStat) {
            $this->assertEquals(1, $surveyStat['surveyAverageOverallRating']);
        }
    }
}