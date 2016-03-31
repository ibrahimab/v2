<?php
namespace AppBundle\Service\Api\Search\Filter;

use AppBundle\Service\Api\Search\Filter\Manager;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Converter
{
    /**
     * @var array
     */
    private $mapper = [

        'vf_kenm' => [

            2 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_CATERING,
            ],

            3 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_SWIMMING_POOL,
            ],

            4 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_SAUNA,
            ],

            5 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_PRIVATE_SAUNA,
            ],

            6 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_PETS_ALLOWED,
            ],

            7 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_FIREPLACE,
            ],

            43 => [

                'filter' => Manager::FILTER_THEME,
                'value'  => Manager::FILTER_THEME_KIDS,
            ],

            44 => [

                'filter' => Manager::FILTER_THEME,
                'value'  => Manager::FILTER_THEME_CHARMING_PLACES,
            ],

            45 => [

                'filter' => Manager::FILTER_THEME,
                'value'  => Manager::FILTER_THEME_10_FOR_APRES_SKI,
            ],

            46 => [

                'filter' => Manager::FILTER_THEME,
                'value'  => Manager::FILTER_THEME_WINTER_WELLNESS,
            ],

            47 => [

                'filter' => Manager::FILTER_THEME,
                'value'  => Manager::FILTER_THEME_SUPER_SKI_STATIONS,
            ],

            50 => [

                'filter' => Manager::FILTER_FACILITY,
                'value'  => Manager::FILTER_FACILITY_INTERNET_WIFI,
            ],
        ],

        'vf_piste' => [

            1 => [

                'filter' => Manager::FILTER_DISTANCE,
                'value'  => Manager::FILTER_DISTANCE_BY_SLOPE,
            ],

            2 => [

                'filter' => Manager::FILTER_DISTANCE,
                'value'  => Manager::FILTER_DISTANCE_MAX_250,
            ],

            3 => [

                'filter' => Manager::FILTER_DISTANCE,
                'value'  => Manager::FILTER_DISTANCE_MAX_500,
            ],

            4 => [

                'filter' => Manager::FILTER_DISTANCE,
                'value'  => Manager::FILTER_DISTANCE_MAX_1000,
            ],
        ],

        'vf_km' => [

            1 => [

                'filter' => Manager::FILTER_LENGTH,
                'value'  => Manager::FILTER_LENGTH_MAX_100,
            ],

            2 => [

                'filter' => Manager::FILTER_LENGTH,
                'value'  => Manager::FILTER_LENGTH_MIN_100,
            ],

            3 => [

                'filter' => Manager::FILTER_LENGTH,
                'value'  => Manager::FILTER_LENGTH_MIN_200,
            ],

            4 => [

                'filter' => Manager::FILTER_LENGTH,
                'value'  => Manager::FILTER_LENGTH_MIN_400,
            ],
        ],
    ];

    /**
     * @param string $filters
     *
     * @return array
     */
    public function convert($filters)
    {
        $parts   = explode('&', $filters);
        $results = [];

        foreach ($parts as $part) {

            if (1 === preg_match('/^(?<kind>.+?)(?<filter>[0-9]+)=1$/', $part, $match)) {

                $converted = $this->map($match['kind'], $match['filter']);

                if (null !== $converted) {
                    $results[] = new Filter((int)$converted['filter'], (int)$converted['value']);
                }
            }
        }

        return $results;
    }

    /**
     * @param integer $kind
     * @param integer $filter
     *
     * @return integer|null
     */
    public function map($kind, $filter)
    {
        return (isset($this->mapper[$kind]) && isset($this->mapper[$kind][$filter]) ? $this->mapper[$kind][$filter] : null);
    }
}