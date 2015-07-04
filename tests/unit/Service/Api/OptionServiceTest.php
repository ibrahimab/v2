<?php
namespace AppBundle\Tests\Unit\Service\Api;
use       AppBundle\Service\Api\Option\OptionService;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class OptionServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var OptionService
     */
    protected $optionService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->optionService    = $this->serviceContainer->get('app.api.option');

        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }

    protected function _after()
    {
        $this->optionService = null;
    }

    public function testGetTravelInsurancesDescription()
    {
        // $description = $this->optionService->getTravelInsurancesDescription();

        // $this->assertNotNull($description);
        // $this->assertContains('Lorem', $description);
    }
}