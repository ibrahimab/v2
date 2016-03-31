<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Builder;
use AppBundle\Service\Api\Search\Filter\Manager;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class FilterBuilderTest extends \Codeception\TestCase\Test
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @var Builder
     */
    private $builder;

    /** @before */
    public function setup()
    {
        parent::setup();

        $this->filters = [

            1 => 2,
            2 => 2,
            3 => [

                0 => 3,
                1 => 4,
            ],
            5 => [

                0 => 3,
                1 => 4,
            ],
        ];

        $this->builder = new Builder($this->filters);
    }

    /** @test */
    public function check_parser()
    {
        $parsed  = $this->builder->parse()->all();
        $manager = new Manager();

        foreach ($parsed as $filter) {

            $this->assertTrue(Manager::exists($filter->getFilter()));

            if (is_array($filter->getValue())) {
                $this->assertTrue(Manager::multiple($filter->getFilter()));
            } else {
                $this->assertFalse(Manager::multiple($filter->getValue()));
            }

            $this->assertTrue($this->builder->has($filter->getFilter()));
            $this->assertEquals($filter->getValue(), $this->builder->filter($filter->getFilter())->getValue());
        }
    }
}