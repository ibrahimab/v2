<?php
namespace AppBundle\Tests\Unit\Service\Api;


class RegionServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->regionService    = $this->serviceContainer->get('service.api.region');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->regionService = null;
    }

    public function testGetRegions()
    {
        $limit   = 5;
        $regions = $this->regionService->all(['limit' => $limit]);

        $this->assertInternalType('array', $regions);
        $this->assertCount($limit, $regions);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $regions);
    }

    public function testGetRegion()
    {
        $region = $this->regionService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $region);
    }

    public function testNotFoundRegion()
    {
        $region = $this->regionService->find(['id' => 'non-existant']);

        $this->assertNull($region);
    }
    
    public function testGetRegionByLocaleName()
    {
        $region = $this->regionService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $region);

        $localeRegionNL = $this->regionService->findByLocaleName($region->getName(), 'nl')[0]->getRegion();
        $localeRegionEN = $this->regionService->findByLocaleName($region->getEnglishName(), 'en')[0]->getRegion();
        $localeRegionDE = $this->regionService->findByLocaleName($region->getGermanName(), 'de')[0]->getRegion();
        
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $localeRegionNL, 'Dutch variant not found');
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $localeRegionEN, 'English variant not found');
        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $localeRegionDE, 'German variant not found');
        
        $this->assertEquals($region->getId(), $localeRegionNL->getId());
        $this->assertEquals($region->getId(), $localeRegionEN->getId());
        $this->assertEquals($region->getId(), $localeRegionDE->getId());
    }
}