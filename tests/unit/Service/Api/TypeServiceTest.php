<?php
namespace AppBundle\Tests\Unit\Service\Api;


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
    
    /**
     * @var \AppBundle\Service\Api\RegionService
     */
    protected $regionService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->typeService      = $this->serviceContainer->get('app.api.type');
        $this->regionService    = $this->serviceContainer->get('app.api.region');
        $this->placeService     = $this->serviceContainer->get('app.api.place');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->typeService   = null;
        $this->regionService = null;
        $this->placeService  = null;
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
    
    public function testCountByRegions()
    {
        $regions = $this->regionService->all(['where' => ['id' => [1]]]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $regions);
        
        // counting accommodations
        $accommodationsCount = $this->typeService->countByRegions($regions);

        // asserting that region ID = 1 has 1 accommodation
        $this->assertArrayHasKey(1, $accommodationsCount);
        $this->assertSame(1, $accommodationsCount[1]);
    }
    
    public function testCountByPlace()
    {
        $place = $this->placeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
        
        // counting accommodations
        $accommodationsCount = $this->typeService->countByPlace($place);

        // asserting that place ID = 1 has 1 accommodation
        $this->assertEquals(1, $accommodationsCount);
    }
    
    public function testGetTypesByPlace()
    {
        $place = $this->placeService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
        
        $types = $this->typeService->findByPlace($place, 2);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $types);
    }
    
    public function testFindTypeById()
    {
        /**
         * This service endpoint needs to find all the associations without additional
         * queries to the database
         */
        $debugLogger   = new \Doctrine\DBAL\Logging\DebugStack();
        $profiler      = $this->serviceContainer->get('doctrine')->getConnection()->getConfiguration()->setSQLLogger($debugLogger);
        $queriesStart  = count($debugLogger->queries);
        
        $type          = $this->typeService->findById(1);
        $accommodation = $type->getAccommodation();
        $place         = $accommodation->getPlace();
        $region        = $place->getRegion();
        $country       = $place->getCountry();
        
        // forcing queries
        $accommodation->getName();
        $place->getName();
        $region->getName();
        $country->getName();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $type);
        $this->assertInstanceOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodation);
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $region);
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);
        
        $queriesEnd = count($debugLogger->queries);
        
        $this->assertEquals(1, ($queriesEnd - $queriesStart));
    }
}