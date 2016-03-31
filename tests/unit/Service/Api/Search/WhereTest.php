<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Builder\Where;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class WhereTest extends \Codeception\TestCase\Test
{
    /** @test */
    public function create_where_weekend_ski_clause()
    {
        $value      = rand(0, 1);
        $weekendski = new Where(Where::WHERE_WEEKEND_SKI, $value);

        $this->assertEquals(Where::WHERE_WEEKEND_SKI, $weekendski->getClause());
        $this->assertEquals($value, $weekendski->getValue());
    }

    /** @test */
    public function create_where_accommodation_clause()
    {
        $value         = rand(1, 100);
        $accommodation = new Where(Where::WHERE_ACCOMMODATION, $value);

        $this->assertEquals(Where::WHERE_ACCOMMODATION, $accommodation->getClause());
        $this->assertEquals($value, $accommodation->getValue());
    }

    /** @test */
    public function create_where_country_clause()
    {
        $countries = ['Frankrijk', 'Oostenrijk', 'Zwitserland', 'Italië'];
        $value     = $countries[array_rand($countries)];
        $country   = new Where(Where::WHERE_COUNTRY, $value);

        $this->assertEquals(Where::WHERE_COUNTRY, $country->getClause());
        $this->assertEquals($value, $country->getValue());
    }

    /** @test */
    public function create_where_region_clause()
    {
        $regions  = range('A', 'Z');
        $value    = $regions[array_rand($regions)];
        $region   = new Where(Where::WHERE_REGION, $value);

        $this->assertEquals(Where::WHERE_REGION, $region->getClause());
        $this->assertEquals($value, $region->getValue());
    }

    /** @test */
    public function create_where_place_clause()
    {
        $places   = range('A', 'Z');
        $value    = $places[array_rand($places)];
        $place    = new Where(Where::WHERE_PLACE, $value);

        $this->assertEquals(Where::WHERE_PLACE, $place->getClause());
        $this->assertEquals($value, $place->getValue());
    }

    /** @test */
    public function create_where_bedrooms_clause()
    {
        $value    = rand(1, 10);
        $bedrooms = new Where(Where::WHERE_BEDROOMS, $value);

        $this->assertEquals(Where::WHERE_BEDROOMS, $bedrooms->getClause());
        $this->assertEquals($value, $bedrooms->getValue());
    }

    /** @test */
    public function create_where_bathrooms_clause()
    {
        $value     = rand(1, 10);
        $bathrooms = new Where(Where::WHERE_BATHROOMS, $value);

        $this->assertEquals(Where::WHERE_BATHROOMS, $bathrooms->getClause());
        $this->assertEquals($value, $bathrooms->getValue());
    }

    /** @test */
    public function create_where_persons_clause()
    {
        $value   = rand(1, 10);
        $persons = new Where(Where::WHERE_PERSONS, $value);

        $this->assertEquals(Where::WHERE_PERSONS, $persons->getClause());
        $this->assertEquals($value, $persons->getValue());
    }

    /** @test */
    public function create_where_freesearch_clause()
    {
        $string     = range('A', 'Z');
        $string     = array_merge($string, range('a', 'z'));
        $string     = array_merge($string, range(0, 9));
        $string     = str_shuffle(implode('', $string));
        $freesearch = new Where(Where::WHERE_FREESEARCH, $string);

        $this->assertEquals(Where::WHERE_FREESEARCH, $freesearch->getClause());
        $this->assertEquals($string, $freesearch->getValue());
    }

    /**
     * @test
     * @expectedException \AppBundle\Service\Api\Search\SearchException
     */
    public function throw_exception_when_non_existing_clause_is_created()
    {
        new Where('non-existing-where-clause', 'not-important-value');
    }
}