<?php
namespace Api;

class AccommodationsTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\AccommodationService
     */
    protected $accommodationService;

    protected function _before()
    {
        $this->serviceContainer     = $this->getModule('Symfony2')->container;
        $this->accommodationService = $this->serviceContainer->get('api.accommodation.service');
    }

    public function testGetAccommodations()
    {
        $this->specify('Get accommodations', function() {
            
            $limit          = 5;
            $accommodations = $this->accommodationService->all(['limit' => $limit]);
            $total          = count($accommodations);
            
            $this->assertInternalType('array', $accommodations);
            $this->assertCount($limit, $accommodations);
            $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodations);
        });
    }
    
    public function testGetAccommodationBy() {
        
        $this->specify('Getting accommodation by ID', function() {
        
            $accommodation = $this->accommodationService->find(['id' => 1]);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodation);
            $this->assertEquals(1, $accommodation->getId());
            $this->assertEquals('Accommodation #1', $accommodation->getName());
        });
        
        $this->specify('Getting accommodation by Name', function() {
        
            $accommodation = $this->accommodationService->find(['name' => 'Accommodation #1']);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodation);
            $this->assertEquals(1, $accommodation->getId());
            $this->assertEquals('Accommodation #1', $accommodation->getName());
        });
    }
    
    public function testNotFoundAccommodations()
    {
        $this->specify('Getting null when looking for a single non-existant accommodation', function() {
            
            $accommodation = $this->accommodationService->find(['id' => 'non-existant']);
            $this->assertNull($accommodation);
        });
        
        $this->specify('Getting empty array when looking for accommodations using a non-existant critera', function() {
            
            $accommodations = $this->accommodationService->all(['where' => ['name' => 'non-existant']]);
            $this->assertInternalType('array', $accommodations);
            $this->assertEmpty($accommodations);
        });
    }
}