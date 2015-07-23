<?php
namespace AppBundle\Service;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\Api\Search\Result\Resultset;

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
     * Constructor
     *
     * @param Resultset $resultset
     * @param array $filters
     */
    public function __construct($resultset, $filters)
    {
        $this->resultset = $resultset;
        $this->filters   = $filters;
        $this->facets    = [];
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
        $results = $this->resultset->results;

        foreach ($results as $accommodation) {
            $this->accommodation($accommodation);
        }
    }

    /**
     * @param array $accommodation
     */
    public function accommodation($accommodation)
    {
        $types = $accommodation['types'];

        foreach ($types as $type) {
            $this->filters($accommodation, $type);
        }
    }

    /**
     * @param array $accommodation
     * @param array $type
     */
    public function filters($accommodation, $type)
    {
        foreach ($this->filters as $filter => $values) {
            $this->filter($accommodation, $type, $filter, $values);
        }
    }

    /**
     * @param array $accommodation
     * @param array $type
     * @param integer $filter
     * @param integer $value
     */
    public function filter($accommodation, $type, $filter, $values)
    {
        foreach ($values as $value) {

            switch ($filter) {

                case FilterService::FILTER_DISTANCE:
                    $this->distance($accommodation, $filter, $value);
                break;

                case FilterService::FILTER_LENGTH:
                    $this->length($accommodation, $filter, $value);
                break;

                case FilterService::FILTER_FACILITY:
                    $this->facility($accommodation, $type, $filter, $value);
                break;

                case FilterService::FILTER_THEME:
                    $this->theme($accommodation, $type, $filter, $value);
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
     * @param array $accommodation
     * @param integer $filter
     * @param integer $value
     */
    public function distance($accommodation, $filter, $value)
    {
        $distanceSlope = $accommodation['distanceSlope'];
        switch ($value) {

            case FilterService::FILTER_DISTANCE_BY_SLOPE:

                if ($distanceSlope <= FilterBuilder::VALUE_DISTANCE_BY_SLOPE) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_DISTANCE_MAX_250:

                if ($distanceSlope <= 250) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_DISTANCE_MAX_500:

                if ($distanceSlope <= 500) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_DISTANCE_MAX_1000:

                if ($distanceSlope <= 1000) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $accommodation
     * @param integer $filter
     * @param integer $value
     */
    public function length($accommodation, $filter, $value)
    {
        $length = $accommodation['place']['region']['totalSlopesDistance'];
        switch ($value) {

            case FilterService::FILTER_LENGTH_MAX_100:

                if ($length < 100) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_LENGTH_MIN_100:

                if ($length >= 100) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_LENGTH_MIN_200:

                if ($length >= 200) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_LENGTH_MIN_400:

                if ($length >= 400) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $accommodation
     * @param array $type
     * @param integer $filter
     * @param integer $value
     */
    public function facility($accommodation, $type, $filter, $value)
    {
        switch ($value) {

            case FilterService::FILTER_FACILITY_CATERING:

                if (in_array(1, $accommodation['features']) || in_array(1, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_INTERNET_WIFI:

                if (in_array(21, $accommodation['features']) || in_array(23, $accommodation['features']) || in_array(20, $type['features']) || in_array(22, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_SWIMMING_POOL:

                if (in_array(4, $accommodation['features']) || in_array(11, $accommodation['features']) || in_array(4, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_SAUNA:

                if (in_array(3, $accommodation['features']) || in_array(10, $accommodation['features']) || in_array(3, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:

                if (in_array(3, $accommodation['features']) || in_array(3, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_PETS_ALLOWED:

                if (in_array(13, $accommodation['features']) || in_array(11, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_FACILITY_FIREPLACE:

                if (in_array(12, $accommodation['features']) || in_array(10, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }

    /**
     * @param array $accommodation
     * @param array $type
     * @param integer $filter
     * @param integer $value
     */
    public function theme($accommodation, $type, $filter, $value)
    {
        switch ($value) {

            case FilterService::FILTER_THEME_KIDS:

                if (in_array(5, $accommodation['features']) || in_array(5, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_THEME_CHARMING_PLACES:

                if (in_array(13, $accommodation['place']['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_THEME_WINTER_WELLNESS:

                if (in_array(9, $accommodation['features']) || in_array(9, $type['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_THEME_SUPER_SKI_STATIONS:

                if (in_array(14, $accommodation['place']['features'])) {
                    $this->increment($filter, $value);
                }

            break;

            case FilterService::FILTER_THEME_10_FOR_APRES_SKI:

                if (in_array(6, $accommodation['place']['features'])) {
                    $this->increment($filter, $value);
                }

            break;
        }
    }
}