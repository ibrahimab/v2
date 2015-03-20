<?php
namespace Api;


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
}