<?php
namespace AppBundle\Service\Api\Search;

use AppBundle\Service\Api\Search\Filter\Manager;
use AppBundle\Service\Api\Search\Result\Resultset;
use AppBundle\Service\Api\Search\Filter\Manager as FilterManager;

/**
 * FacetService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class FacetService
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @var Resultset
     */
    private $resultset;

    /**
     * @var array
     */
    private $facets;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor
     *
     * @param Resultset $resultset
     * @param array     $filters
     * @param array     $config
     */
    public function __construct(Resultset $resultset, $filters, array $config)
    {
        $this->resultset = $resultset;
        $this->filters   = $filters;
        $this->facets    = [];
        $this->config    = $config;
    }

    /**
     * @param integer $filter
     * @param integer $value
     * @return boolean
     */
    public function has($filter, $value)
    {
        return isset($this->filters[$filter]) && in_array($value, $this->filters[$filter]);
    }

    /**
     * @param integer $filter
     * @param integer $value
     * @return integer
     */
    public function get($filter, $value)
    {
        if (!isset($this->facets[$filter])) {
            return 0;
        }

        if (!isset($this->facets[$filter][$value])) {
            return 0;
        }

        return $this->facets[$filter][$value];
    }

    /**
     * @param integer|null $filter
     * @return array
     */
    public function facets($filter = null)
    {
        return (null !== $filter && isset($this->facets[$filter]) ? $this->facets[$filter] : $this->facets);
    }

    /**
     * Calculate the facets
     */
    public function calculate()
    {
        foreach ($this->resultset->results as $result) {
            $this->result($result);
        }
    }

    /**
     * @param array $result
     */
    public function result($result)
    {
        foreach ($result as $row) {
            $this->filters($row);
        }
    }

    /**
     * @param array $row
     */
    public function filters(&$row)
    {
        foreach ($this->filters as $filter => $values) {
            $this->filter($row, $filter, $values);
        }
    }

    /**
     * @param array $row
     * @param integer $filter
     * @param integer $value
     */
    public function filter(&$row, $filter, $values)
    {
        foreach ($values as $value) {

            switch ($filter) {

                case FilterManager::FILTER_DISTANCE:
                    $this->distance($row, $filter, $value);
                break;

                case FilterManager::FILTER_LENGTH:
                    $this->length($row, $filter, $value);
                break;

                case FilterManager::FILTER_FACILITY:
                    $this->facility($row, $filter, $value);
                break;

                case FilterManager::FILTER_THEME:
                    $this->theme($row, $filter, $value);
                break;
            }
        }
    }

    /**
     * @param integer $filter
     * @param integer $value
     */
    public function increment($filter, $value)
    {
        if (!isset($this->facets[$filter])) {
            $this->facets[$filter] = [];
        }

        if (!isset($this->facets[$filter][$value])) {
            $this->facets[$filter][$value] = 0;
        }

        $this->facets[$filter][$value] += 1;
    }

    /**
     * @param array $row
     * @param integer $filter
     * @param integer $value
     */
    public function distance($row, $filter, $value)
    {
        $distanceSlope = $row['accommodation_distance_slope'];
        switch ($value) {

            case FilterManager::FILTER_DISTANCE_BY_SLOPE:

                if ($distanceSlope <= $this->config['service']['api']['search']['distance_by_slope']) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_DISTANCE_MAX_250:

                if ($distanceSlope <= 250) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_DISTANCE_MAX_500:

                if ($distanceSlope <= 500) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_DISTANCE_MAX_1000:

                if ($distanceSlope <= 1000) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $row
     * @param integer $filter
     * @param integer $value
     */
    public function length($row, $filter, $value)
    {
        $length = $row['region_total_slopes_distance'];
        switch ($value) {

            case FilterManager::FILTER_LENGTH_MAX_100:

                if ($length < 100) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_LENGTH_MIN_100:

                if ($length >= 100) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_LENGTH_MIN_200:

                if ($length >= 200) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_LENGTH_MIN_400:

                if ($length >= 400) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $row
     * @param integer $filter
     * @param integer $value
     */
    public function facility($row, $filter, $value)
    {
        switch ($value) {

            case FilterManager::FILTER_FACILITY_CATERING:

                if (in_array(1, $row['accommodation_features']) || in_array(1, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_INTERNET_WIFI:

                if (in_array(21, $row['accommodation_features']) || in_array(23, $row['accommodation_features']) || in_array(20, $row['type_features']) || in_array(22, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_SWIMMING_POOL:

                if (in_array(4, $row['accommodation_features']) || in_array(11, $row['accommodation_features']) || in_array(4, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_SAUNA:

                if (in_array(3, $row['accommodation_features']) || in_array(10, $row['accommodation_features']) || in_array(3, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_PRIVATE_SAUNA:

                if (in_array(3, $row['accommodation_features']) || in_array(3, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_PETS_ALLOWED:

                if (in_array(13, $row['accommodation_features']) || in_array(11, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_FACILITY_FIREPLACE:

                if (in_array(12, $row['accommodation_features']) || in_array(10, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $row
     * @param integer $filter
     * @param integer $value
     */
    public function theme($row, $filter, $value)
    {
        switch ($value) {

            case FilterManager::FILTER_THEME_KIDS:

                if (in_array(5, $row['accommodation_features']) || in_array(5, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_THEME_CHARMING_PLACES:

                if (in_array(13, $row['place_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_THEME_WINTER_WELLNESS:

                if (in_array(9, $row['accommodation_features']) || in_array(9, $row['type_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_THEME_SUPER_SKI_STATIONS:

                if (in_array(14, $row['place_features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterManager::FILTER_THEME_10_FOR_APRES_SKI:

                if (in_array(6, $row['place_features'])) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @return array
     */
    public static function activeFilters()
    {
        return [

            FilterManager::FILTER_DISTANCE => [FilterManager::FILTER_DISTANCE_BY_SLOPE, FilterManager::FILTER_DISTANCE_MAX_250, FilterManager::FILTER_DISTANCE_MAX_500, FilterManager::FILTER_DISTANCE_MAX_1000],
            FilterManager::FILTER_LENGTH   => [FilterManager::FILTER_LENGTH_MAX_100, FilterManager::FILTER_LENGTH_MIN_100, FilterManager::FILTER_LENGTH_MIN_200, FilterManager::FILTER_LENGTH_MIN_400],
            FilterManager::FILTER_FACILITY => [FilterManager::FILTER_FACILITY_CATERING, FilterManager::FILTER_FACILITY_INTERNET_WIFI, FilterManager::FILTER_FACILITY_SWIMMING_POOL, FilterManager::FILTER_FACILITY_SAUNA, FilterManager::FILTER_FACILITY_PRIVATE_SAUNA, FilterManager::FILTER_FACILITY_PETS_ALLOWED, FilterManager::FILTER_FACILITY_FIREPLACE],
            FilterManager::FILTER_THEME    => [FilterManager::FILTER_THEME_KIDS, FilterManager::FILTER_THEME_CHARMING_PLACES, FilterManager::FILTER_THEME_10_FOR_APRES_SKI, FilterManager::FILTER_THEME_SUPER_SKI_STATIONS, FilterManager::FILTER_THEME_WINTER_WELLNESS],
        ];
    }
}