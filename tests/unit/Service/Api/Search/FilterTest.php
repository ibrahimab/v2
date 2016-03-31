<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Filter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class FilterTest extends \Codeception\TestCase\Test
{
    /**
     * @var integer
     */
    private $filterId;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var Filter
     */
    private $filter;

    /** @before */
    public function setup()
    {
        parent::setup();

        $this->filterId = rand(1, 1000);
        $this->value    = rand(1, 1000);
        $this->filter   = new Filter($this->filterId, $this->value);
    }

    /** @test */
    public function get_filter()
    {
        $this->assertEquals($this->filterId, $this->filter->getFilter());
    }

    /** @test */
    public function get_value()
    {
        $this->assertEquals($this->value, $this->filter->getValue());
    }
}