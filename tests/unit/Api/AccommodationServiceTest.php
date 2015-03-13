<?php
namespace Api;

class AccommodationsTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\AccommodationService
     */
    protected $accommodationService;

    protected function _before()
    {
        $this->serviceContainer     = $this->getModule('Symfony2')->container;
        $this->accommodationService = $this->serviceContainer->get('service.api.accommodation');
    }
    
    protected function _after()
    {
        $this->accommodationService = null;
    }

    public function testGetAccommodations()
    {
           $limit          = 5;
           $accommodations = $this->accommodationService->all(['limit' => $limit]);
           $total          = count($accommodations);
           
           $this->assertInternalType('array', $accommodations);
           $this->assertCount($limit, $accommodations);
           $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodations);
    }
    
    public function testGetAccommodationBy() {
        
            // Getting accommodation by ID
            $accommodation = $this->accommodationService->find(['id' => 1]);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodation);
            $this->assertEquals(1, $accommodation->getId());
            $this->assertEquals('Accommodation #1', $accommodation->getName());

        
            // Getting accommodation by Name
            $accommodation = $this->accommodationService->find(['name' => 'Accommodation #1']);
            
            $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodation);
            $this->assertEquals(1, $accommodation->getId());
            $this->assertEquals('Accommodation #1', $accommodation->getName());
    }
    
    public function testNotFoundAccommodations()
    {
            // Getting null when looking for a single non-existant accommodation
            $accommodation = $this->accommodationService->find(['id' => 'non-existant']);

            $this->assertNull($accommodation);

            // Getting empty array when looking for accommodations using a non-existant critera            
            $accommodations = $this->accommodationService->all(['where' => ['name' => 'non-existant']]);

            $this->assertInternalType('array', $accommodations);
            $this->assertEmpty($accommodations);
    }
    
    public function testGetTypes()
    {
        // first getting single accommodation
        $accommodation = $this->accommodationService->find();
        
        // then get types
        $types = $accommodation->getTypes();
        
        // asserting they all implement the interface
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $types);
    }
    
    public function testGetPlace()
    {
        // getting accommodations
        $accommodation = $this->accommodationService->find();
        
        // get place
        $place = $accommodation->getPlace();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
    }
}