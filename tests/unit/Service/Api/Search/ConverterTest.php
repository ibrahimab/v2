<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Converter;
use AppBundle\Service\Api\Search\Filter\Manager;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class ConverterTest extends \Codeception\TestCase\Test
{
    /** @before */
    public function setup()
    {
        parent::setup();
        $this->converter = new Converter();
    }

    /** @test */
    public function parse_vf_kenm()
    {
        $params         = 'vf_kenm2=1&vf_kenm3=1&vf_kenm4=1&vf_kenm5=1&vf_kenm6=1&vf_kenm7=1&vf_kenm43=1&vf_kenm44=1&vf_kenm45=1&vf_kenm46=1&vf_kenm47=1&vf_kenm50=1';
        $converted      = $this->converter->convert($params);

        $catering       = $converted[0];
        $swimmingPool   = $converted[1];
        $sauna          = $converted[2];
        $privateSauna   = $converted[3];
        $petsAllowed    = $converted[4];
        $fireplace      = $converted[5];
        $kids           = $converted[6];
        $charmingPlaces = $converted[7];
        $apresSki       = $converted[8];
        $winterWellness = $converted[9];
        $superSki       = $converted[10];
        $internetWifi   = $converted[11];

        $this->assertEquals(Manager::FILTER_FACILITY, $catering->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_CATERING, $catering->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $swimmingPool->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_SWIMMING_POOL, $swimmingPool->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $sauna->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_SAUNA, $sauna->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $privateSauna->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_PRIVATE_SAUNA, $privateSauna->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $petsAllowed->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_PETS_ALLOWED, $petsAllowed->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $fireplace->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_FIREPLACE, $fireplace->getValue());

        $this->assertEquals(Manager::FILTER_THEME, $kids->getFilter());
        $this->assertEquals(Manager::FILTER_THEME_KIDS, $kids->getValue());

        $this->assertEquals(Manager::FILTER_THEME, $charmingPlaces->getFilter());
        $this->assertEquals(Manager::FILTER_THEME_CHARMING_PLACES, $charmingPlaces->getValue());

        $this->assertEquals(Manager::FILTER_THEME, $apresSki->getFilter());
        $this->assertEquals(Manager::FILTER_THEME_10_FOR_APRES_SKI, $apresSki->getValue());

        $this->assertEquals(Manager::FILTER_THEME, $winterWellness->getFilter());
        $this->assertEquals(Manager::FILTER_THEME_WINTER_WELLNESS, $winterWellness->getValue());

        $this->assertEquals(Manager::FILTER_THEME, $superSki->getFilter());
        $this->assertEquals(Manager::FILTER_THEME_SUPER_SKI_STATIONS, $superSki->getValue());

        $this->assertEquals(Manager::FILTER_FACILITY, $internetWifi->getFilter());
        $this->assertEquals(Manager::FILTER_FACILITY_INTERNET_WIFI, $internetWifi->getValue());
    }

    /** @test */
    public function parse_vf_piste()
    {
        $params         = 'vf_piste1=1&vf_piste2=1&vf_piste3=1&vf_piste4=1';
        $converted      = $this->converter->convert($params);

        $slope          = $converted[0];
        $max250         = $converted[1];
        $max500         = $converted[2];
        $max1000        = $converted[3];

        $this->assertEquals(Manager::FILTER_DISTANCE, $slope->getFilter());
        $this->assertEquals(Manager::FILTER_DISTANCE_BY_SLOPE, $slope->getValue());

        $this->assertEquals(Manager::FILTER_DISTANCE, $max250->getFilter());
        $this->assertEquals(Manager::FILTER_DISTANCE_MAX_250, $max250->getValue());

        $this->assertEquals(Manager::FILTER_DISTANCE, $max500->getFilter());
        $this->assertEquals(Manager::FILTER_DISTANCE_MAX_500, $max500->getValue());

        $this->assertEquals(Manager::FILTER_DISTANCE, $max1000->getFilter());
        $this->assertEquals(Manager::FILTER_DISTANCE_MAX_1000, $max1000->getValue());
    }

    public function parse_vf_km()
    {
        $params         = 'vf_km1=1&vf_km2=1&vf_km3=1&vf_km4=1';
        $converted      = $this->converter->convert($params);

        $max100         = $converted[0];
        $min100         = $converted[1];
        $min200         = $converted[2];
        $min400         = $converted[3];

        $this->assertEquals(Manager::FILTER_LENGTH, $max100->getFilter());
        $this->assertEquals(Manager::FILTER_LENGTH_MAX_100, $max100->getValue());

        $this->assertEquals(Manager::FILTER_LENGTH, $min100->getFilter());
        $this->assertEquals(Manager::FILTER_LENGTH_MIN_100, $min100->getValue());

        $this->assertEquals(Manager::FILTER_LENGTH, $min200->getFilter());
        $this->assertEquals(Manager::FILTER_LENGTH_MIN_200, $min200->getValue());

        $this->assertEquals(Manager::FILTER_LENGTH, $min400->getFilter());
        $this->assertEquals(Manager::FILTER_LENGTH_MIN_400, $min400->getValue());
    }
}