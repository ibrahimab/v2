<?php
namespace Api;


class TypeServiceTest extends \Codeception\TestCase\Test
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
        $this->typeService      = $this->serviceContainer->get('service.api.type');
    }
    
    protected function _after()
    {
        $this->typeService = null;
    }

    public function testGetTypes()
    {
        // Get types
        $limit = 5;
        $types = $this->typeService->all(['limit' => $limit]);
        
        $this->assertInternalType('array', $types);
        $this->assertCount($limit, $types);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $types);
    }
    
    public function testGetSingleType()
    {
        $type = $this->typeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
    }
    
    public function testNotFoundType()
    {
        $type = $this->typeService->find(['id' => 'non-existant']);
        $this->assertNull($type);
    }

    public function testGetTypeWithAccommodation()
    {
            // Get type with his accommodation
            $type = $this->typeService->find(['id' => 1]);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $type->getAccommodation());
    }
    
    public function testSurveys()
    {
        // get type
        $type = $this->typeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        
        // get surveys
        $surveys = $type->getSurveys();
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface', $surveys);
    }
}