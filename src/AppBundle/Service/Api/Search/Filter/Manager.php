<?php
namespace AppBundle\Service\Api\Search\Filter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Manager
{
    /** @var integer */
    const FILTER_DISTANCE                 = 1;

    /** @var integer */
    const FILTER_DISTANCE_BY_SLOPE        = 1,
          FILTER_DISTANCE_MAX_250         = 2,
          FILTER_DISTANCE_MAX_500         = 3,
          FILTER_DISTANCE_MAX_1000        = 4;

    /** @var integer */
    const FILTER_LENGTH                   = 2;

    /** @var integer */
    const FILTER_LENGTH_MAX_100           = 1,
          FILTER_LENGTH_MIN_100           = 2,
          FILTER_LENGTH_MIN_200           = 3,
          FILTER_LENGTH_MIN_400           = 4;

    /** @var integer */
    const FILTER_FACILITY                 = 3;

    /** @var integer */
    const FILTER_FACILITY_CATERING        = 1,
          FILTER_FACILITY_INTERNET_WIFI   = 2,
          FILTER_FACILITY_SWIMMING_POOL   = 3,
          FILTER_FACILITY_SAUNA           = 4,
          FILTER_FACILITY_PRIVATE_SAUNA   = 5,
          FILTER_FACILITY_PETS_ALLOWED    = 6,
          FILTER_FACILITY_FIREPLACE       = 7;

    /** @var integer */
    const FILTER_THEME                    = 5;

    /** @var integer */
    const FILTER_THEME_KIDS               = 1,
          FILTER_THEME_CHARMING_PLACES    = 2,
          FILTER_THEME_10_FOR_APRES_SKI   = 3,
          FILTER_THEME_SUPER_SKI_STATIONS = 4,
          FILTER_THEME_WINTER_WELLNESS    = 5;

    /**
     * @var array
     */
    private static $filters = [

        self::FILTER_DISTANCE => [
            'multiple' => false,
        ],

        self::FILTER_LENGTH   => [
            'multiple' => false,
        ],

        self::FILTER_FACILITY => [
            'multiple' => true,
        ],

        self::FILTER_THEME    => [
            'multiple' => true,
        ],
    ];

    /**
     * @param integer $filter
     *
     * @return boolean
     */
    public static function exists($filter)
    {
        return isset(self::$filters[$filter]);
    }

    /**
     * @param integer $filter
     *
     * @return boolean
     */
    public static function multiple($filter)
    {
        return self::$filters[$filter]['multiple'];
    }
}