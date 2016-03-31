<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Builder\Builder as SearchBuilder;
use AppBundle\Service\Api\Search\Filter\Builder  as FilterBuilder;
use AppBundle\Service\Api\Search\Builder\Sort;
use AppBundle\Service\Api\Search\Builder\Where;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class SearchBuilderTest extends \Codeception\TestCase\Test
{
    /**
     * @var SearchBuilder
     */
    private $builder;

    /** @before */
    public function setup()
    {
        parent::setup();

        $this->builder = new SearchBuilder();

        $this->clauses = [new Where(Where::WHERE_ACCOMMODATION, rand(0, 10)),
                          new Where(Where::WHERE_BATHROOMS, rand(0, 10)),
                          new Where(Where::WHERE_COUNTRY, rand(0, 10))];

        foreach ($this->clauses as $clause) {
            $this->builder->addClause($clause);
        }
    }

    /** @test */
    public function check_clauses()
    {
        $this->assertEquals($this->clauses, $this->builder->getClauses());
    }
}