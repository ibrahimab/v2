<?php
namespace Api;


class PlaceServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->placeService     = $this->serviceContainer->get('api.place.service');
    }
    
    protected function _after()
    {
        $this->placeService = null;
    }

    public function testGetPlaces()
    {
        $limit  = 5;
        $places = $this->placeService->all(['limit' => $limit]);
        
        $this->assertInternalType('array', $places);
        $this->assertCount($limit, $places);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
    }
    
    public function testGetPlace()
    {
        $place = $this->placeService->find();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
    }
    
    public function testNotFoundPlace()
    {
        $place = $this->placeService->find(['id' => 'non-existant']);
        
        $this->assertNull($place);
    }
    
    public function testGetPlaceAndSibling()
    {
        // getting place
        $place = $this->placeService->find();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
        
        // getting sibling
        $sibling = $place->getSibling();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $sibling);
    }
}