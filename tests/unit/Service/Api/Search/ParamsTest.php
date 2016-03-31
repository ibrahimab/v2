<?php
namespace AppBundle\Tests\Unit\Service\Api\Search;

use AppBundle\Service\Api\Search\Params;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class ParamsTest extends \Codeception\TestCase\Test
{
    /**
     * @var array
     */
    private $countries;

    /**
     * @var array
     */
    private $regions;

    /**
     * @var array
     */
    private $places;

    /**
     * @var array
     */
    private $accommodations;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var Params
     */
    private $params;

    /** @before */
    public function setup()
    {
        parent::setup();

        $this->countries = [

            'Frankrijk',
            'Oostenrijk',
        ];

        $this->regions = [

            urlencode('Alpe d\'Huez (Grand Domaine)'),
            urlencode('Les Trois Vallées'),
        ];

        $this->places = [

            urlencode('Les Menuires'),
            urlencode('Val Thorens'),
        ];

        $this->accommodations = [

            urlencode('Abracadabra'),
            urlencode('Almchalet am Katschberg'),
        ];

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

        $get = [

            'c'  => $this->countries,
            'r'  => $this->regions,
            'pl' => $this->places,
            'a'  => $this->accommodations,
            'f'  => $this->filters,
        ];

        $request      = ServerRequestFactory::fromGlobals($_SERVER, $get);
        $this->params = new Params($request);
    }

    /** @test */
    public function get_countries()
    {
        $this->assertEquals($this->countries, $this->params->getCountries());
    }

    /** @test */
    public function get_regions()
    {
        $this->assertEquals($this->regions, $this->params->getRegions());
    }

    /** @test */
    public function get_places()
    {
        $this->assertEquals($this->places, $this->params->getPlaces());
    }

    /** @test */
    public function get_accommodations()
    {
        $this->assertEquals($this->accommodations, $this->params->getAccommodations());
    }

    /** @test */
    public function get_filters()
    {
        $this->assertEquals($this->filters, $this->params->getFilters());
    }
}