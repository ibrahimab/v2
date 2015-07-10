<?php
namespace AppBundle\Tests\Unit\Service\Api;

class SeasonServiceTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\SeasonService
     */
    protected $seasonService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->seasonService    = $this->serviceContainer->get('app.api.season');

        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }

    protected function _after()
    {
        $this->seasonService = null;
    }

    public function testGetInsurancesPolicyCosts()
    {
        $policyCosts = $this->seasonService->getInsurancesPolicyCosts();
        $this->assertGreaterThan(0, $policyCosts);
    }
}