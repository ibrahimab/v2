<?php
namespace Api;


class TypeServiceTest extends \Codeception\TestCase\Test
{
    // BDD mixin
    use \Codeception\Specify;
    
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
        $this->typeService      = $this->serviceContainer->get('api.type.service');
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

    public function testGetTypeWithAccommodation()
    {
            // $this->specify('Get type with his accommodation
            $type = $this->typeService->find(['id' => 1]);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $type->getAccommodation());
    }
}