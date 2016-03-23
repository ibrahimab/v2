<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Builder\Sort;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class SortTest extends \Codeception\TestCase\Test
{
    /** @test */
    public function sort_by_accommodation_name_field()
    {
        $asc  = new Sort(Sort::FIELD_ACCOMMODATION_NAME, Sort::SORT_ASC);
        $desc = new Sort(Sort::FIELD_ACCOMMODATION_NAME, Sort::SORT_DESC);

        $this->assertEquals(Sort::FIELD_ACCOMMODATION_NAME, $asc->getField());
        $this->assertEquals(Sort::SORT_ASC, $asc->getDirection());

        $this->assertEquals(Sort::FIELD_ACCOMMODATION_NAME, $desc->getField());
        $this->assertEquals(Sort::SORT_DESC,$desc->getDirection());
    }

    /** @test */
    public function sort_by_type_price()
    {
        $asc  = new Sort(Sort::FIELD_TYPE_PRICE, Sort::SORT_ASC);
        $desc = new Sort(Sort::FIELD_TYPE_PRICE, Sort::SORT_DESC);

        $this->assertEquals(Sort::FIELD_TYPE_PRICE, $asc->getField());
        $this->assertEquals(Sort::SORT_ASC, $asc->getDirection());

        $this->assertEquals(Sort::FIELD_TYPE_PRICE, $desc->getField());
        $this->assertEquals(Sort::SORT_DESC,$desc->getDirection());
    }

    /** @test */
    public function sort_by_type_search_order()
    {
        $asc  = new Sort(Sort::FIELD_TYPE_SEARCH_ORDER, Sort::SORT_ASC);
        $desc = new Sort(Sort::FIELD_TYPE_SEARCH_ORDER, Sort::SORT_DESC);

        $this->assertEquals(Sort::FIELD_TYPE_SEARCH_ORDER, $asc->getField());
        $this->assertEquals(Sort::SORT_ASC, $asc->getDirection());

        $this->assertEquals(Sort::FIELD_TYPE_SEARCH_ORDER, $desc->getField());
        $this->assertEquals(Sort::SORT_DESC,$desc->getDirection());
    }

    /**
     * @test
     * @expectedException \AppBundle\Service\Api\Search\SearchException
     */
    public function throw_exception_with_illegal_field()
    {
        new Sort('non-existing-field');
    }
}