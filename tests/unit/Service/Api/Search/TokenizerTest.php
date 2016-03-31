<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Manager;
use AppBundle\Service\Api\Search\Filter\Filter;
use AppBundle\Service\Api\Search\Filter\Tokenizer;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class TokenizerTest extends \Codeception\TestCase\Test
{
    /** @test */
    public function setup()
    {
        parent::setup();
        $this->tokenizer = new Tokenizer();
    }

    /** @test */
    public function tokenize_distances()
    {
        $distance = new Filter(Manager::FILTER_DISTANCE, null);
        $slope    = new Filter(Manager::FILTER_DISTANCE, Manager::FILTER_DISTANCE_BY_SLOPE);
        $max250   = new Filter(Manager::FILTER_DISTANCE, Manager::FILTER_DISTANCE_MAX_250);
        $max500   = new Filter(Manager::FILTER_DISTANCE, Manager::FILTER_DISTANCE_MAX_500);
        $max1000  = new Filter(Manager::FILTER_DISTANCE, Manager::FILTER_DISTANCE_MAX_1000);

        $this->assertEquals(Tokenizer::TOKEN_DISTANCE, $this->tokenizer->tokenize($distance));

        $this->assertEquals(Tokenizer::TOKEN_DISTANCE_BY_SLOPE, $this->tokenizer->tokenize($slope, true));
        $this->assertEquals(Tokenizer::TOKEN_DISTANCE_MAX_250, $this->tokenizer->tokenize($max250, true));
        $this->assertEquals(Tokenizer::TOKEN_DISTANCE_MAX_500, $this->tokenizer->tokenize($max500, true));
        $this->assertEquals(Tokenizer::TOKEN_DISTANCE_MAX_1000, $this->tokenizer->tokenize($max1000, true));
    }

    /** @test */
    public function tokenize_length()
    {
        $length = new Filter(Manager::FILTER_LENGTH, null);
        $max100 = new Filter(Manager::FILTER_LENGTH, Manager::FILTER_LENGTH_MAX_100);
        $min100 = new Filter(Manager::FILTER_LENGTH, Manager::FILTER_LENGTH_MIN_100);
        $min200 = new Filter(Manager::FILTER_LENGTH, Manager::FILTER_LENGTH_MIN_200);
        $min400 = new Filter(Manager::FILTER_LENGTH, Manager::FILTER_LENGTH_MIN_400);

        $this->assertEquals(Tokenizer::TOKEN_LENGTH, $this->tokenizer->tokenize($length));

        $this->assertEquals(Tokenizer::TOKEN_LENGTH_MAX_100, $this->tokenizer->tokenize($max100, true));
        $this->assertEquals(Tokenizer::TOKEN_LENGTH_MIN_100, $this->tokenizer->tokenize($min100, true));
        $this->assertEquals(Tokenizer::TOKEN_LENGTH_MIN_200, $this->tokenizer->tokenize($min200, true));
        $this->assertEquals(Tokenizer::TOKEN_LENGTH_MIN_400, $this->tokenizer->tokenize($min400, true));
    }

    /** @test */
    public function tokenize_facility()
    {
        $facility     = new Filter(Manager::FILTER_FACILITY, null);
        $catering     = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_CATERING);
        $internetWifi = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_INTERNET_WIFI);
        $swimmingPool = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_SWIMMING_POOL);
        $sauna        = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_SAUNA);
        $privateSauna = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_PRIVATE_SAUNA);
        $petsAllowed  = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_PETS_ALLOWED);
        $fireplace    = new Filter(Manager::FILTER_FACILITY, Manager::FILTER_FACILITY_FIREPLACE);

        $this->assertEquals(Tokenizer::TOKEN_FACILITY, $this->tokenizer->tokenize($facility));

        $this->assertEquals(Tokenizer::TOKEN_FACILITY_CATERING, $this->tokenizer->tokenize($catering, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_INTERNET_WIFI, $this->tokenizer->tokenize($internetWifi, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_SWIMMING_POOL, $this->tokenizer->tokenize($swimmingPool, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_SAUNA, $this->tokenizer->tokenize($sauna, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_PRIVATE_SAUNA, $this->tokenizer->tokenize($privateSauna, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_PETS_ALLOWED, $this->tokenizer->tokenize($petsAllowed, true));
        $this->assertEquals(Tokenizer::TOKEN_FACILITY_FIREPLACE, $this->tokenizer->tokenize($fireplace, true));
    }

    /** @test */
    public function tokenize_theme()
    {
        $theme          = new Filter(Manager::FILTER_THEME, null);
        $kids           = new Filter(Manager::FILTER_THEME, Manager::FILTER_THEME_KIDS);
        $charmingPlaces = new Filter(Manager::FILTER_THEME, Manager::FILTER_THEME_CHARMING_PLACES);
        $apresSki       = new Filter(Manager::FILTER_THEME, Manager::FILTER_THEME_10_FOR_APRES_SKI);
        $superSki       = new Filter(Manager::FILTER_THEME, Manager::FILTER_THEME_SUPER_SKI_STATIONS);
        $winterWellness = new Filter(Manager::FILTER_THEME, Manager::FILTER_THEME_WINTER_WELLNESS);

        $this->assertEquals(Tokenizer::TOKEN_THEME, $this->tokenizer->tokenize($theme));

        $this->assertEquals(Tokenizer::TOKEN_THEME_KIDS, $this->tokenizer->tokenize($kids, true));
        $this->assertEquals(Tokenizer::TOKEN_THEME_CHARMING_PLACES, $this->tokenizer->tokenize($charmingPlaces, true));
        $this->assertEquals(Tokenizer::TOKEN_THEME_10_FOR_APRES_SKI, $this->tokenizer->tokenize($apresSki, true));
        $this->assertEquals(Tokenizer::TOKEN_THEME_SUPER_SKI_STATIONS, $this->tokenizer->tokenize($superSki, true));
        $this->assertEquals(Tokenizer::TOKEN_THEME_WINTER_WELLNESS, $this->tokenizer->tokenize($winterWellness, true));
    }
}