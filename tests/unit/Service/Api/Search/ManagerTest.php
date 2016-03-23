<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Manager;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class ManagerTest extends \Codeception\TestCase\Test
{
    /**
     * @var Manager
     */
    private $manager;

    /** @before */
    public function setup()
    {
        parent::setup();
        $this->manager = new Manager();
    }

    /** @test */
    public function check_that_distance_exists()
    {
        $this->assertTrue($this->manager->exists(Manager::FILTER_DISTANCE));
    }

    /** @test */
    public function check_that_length_exists()
    {
        $this->assertTrue($this->manager->exists(Manager::FILTER_LENGTH));
    }

    /** @test */
    public function check_that_facility_exists()
    {
        $this->assertTrue($this->manager->exists(Manager::FILTER_FACILITY));
    }

    /** @test */
    public function check_that_theme_exists()
    {
        $this->assertTrue($this->manager->exists(Manager::FILTER_THEME));
    }

    /** @test */
    public function check_that_distance_does_not_allow_multiple_values()
    {
        $this->assertFalse($this->manager->multiple(Manager::FILTER_DISTANCE));
    }

    /** @test */
    public function check_that_length_does_not_allow_multiple_values()
    {
        $this->assertFalse($this->manager->multiple(Manager::FILTER_LENGTH));
    }

    /** @test */
    public function check_that_facility_allows_multiple_values()
    {
        $this->assertTrue($this->manager->multiple(Manager::FILTER_FACILITY));
    }

    /** @test */
    public function check_that_theme_allows_multiple_values()
    {
        $this->assertTrue($this->manager->multiple(Manager::FILTER_THEME));
    }
}