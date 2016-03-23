<?php
namespace AppBundle\Service\Api\Search\Filter;

class Tokenizer
{
    /** @var string */
    const TOKEN_DISTANCE                  = 'filter-distance',
          TOKEN_DISTANCE_BY_SLOPE         = 'filter-distance-by-slope',
          TOKEN_DISTANCE_MAX_250          = 'filter-distance-max-250',
          TOKEN_DISTANCE_MAX_500          = 'filter-distance-max-500',
          TOKEN_DISTANCE_MAX_1000         = 'filter-distance-max-1000';

    /** @var string */
    const TOKEN_LENGTH                    = 'filter-length',
          TOKEN_LENGTH_MAX_100            = 'filter-length-max-100',
          TOKEN_LENGTH_MIN_100            = 'filter-length-min-100',
          TOKEN_LENGTH_MIN_200            = 'filter-length-min-200',
          TOKEN_LENGTH_MIN_400            = 'filter-length-min-400';

    /** @var string */
    const TOKEN_FACILITY                  = 'filter-facility',
          TOKEN_FACILITY_CATERING         = 'filter-facility-catering',
          TOKEN_FACILITY_INTERNET_WIFI    = 'filter-facility-internet-wifi',
          TOKEN_FACILITY_SWIMMING_POOL    = 'filter-facility-swimming-pool',
          TOKEN_FACILITY_SAUNA            = 'filter-facility-sauna',
          TOKEN_FACILITY_PRIVATE_SAUNA    = 'filter-facility-private-sauna',
          TOKEN_FACILITY_PETS_ALLOWED     = 'filter-facility-pets-allowed',
          TOKEN_FACILITY_FIREPLACE        = 'filter-facility-fireplace';

    /** @var string */
    const TOKEN_THEME                     = 'filter-theme',
          TOKEN_THEME_KIDS                = 'filter-theme-kids',
          TOKEN_THEME_CHARMING_PLACES     = 'filter-theme-charming-places',
          TOKEN_THEME_10_FOR_APRES_SKI    = 'filter-theme-10-for-apres-ski',
          TOKEN_THEME_SUPER_SKI_STATIONS  = 'filter-theme-super-ski-stations',
          TOKEN_THEME_WINTER_WELLNESS     = 'filter-theme-winter-wellness';

    /**
     * @param Filter  $filter
     * @param boolean $value
     *
     * @return string
     */
    public function tokenize(Filter $filter, $value = false)
    {
        return (true === $value ? $this->value($filter) : $this->filter($filter));
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function filter(Filter $filter)
    {
        switch ($filter->getFilter()) {

            case Manager::FILTER_DISTANCE:
                $result = self::TOKEN_DISTANCE;
                break;

            case Manager::FILTER_LENGTH:
                $result = self::TOKEN_LENGTH;
                break;

            case Manager::FILTER_FACILITY:
                $result = self::TOKEN_FACILITY;
                break;

            case Manager::FILTER_THEME:
                $result = self::TOKEN_THEME;
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function value(Filter $filter)
    {
        switch ($filter->getFilter()) {

            case Manager::FILTER_DISTANCE:
                $result = $this->distance($filter);
                break;

            case Manager::FILTER_LENGTH:
                $result = $this->length($filter);
                break;

            case Manager::FILTER_FACILITY:
                $result = $this->facility($filter);
                break;

            case Manager::FILTER_THEME:
                $result = $this->theme($filter);
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function distance(Filter $filter)
    {
        switch ($filter->getValue()) {

            case Manager::FILTER_DISTANCE_BY_SLOPE:
                $result = self::TOKEN_DISTANCE_BY_SLOPE;
                break;

            case Manager::FILTER_DISTANCE_MAX_250:
                $result = self::TOKEN_DISTANCE_MAX_250;
                break;

            case Manager::FILTER_DISTANCE_MAX_500:
                $result = self::TOKEN_DISTANCE_MAX_500;
                break;

            case Manager::FILTER_DISTANCE_MAX_1000:
                $result = self::TOKEN_DISTANCE_MAX_1000;
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function length(Filter $filter)
    {
        switch ($filter->getValue()) {

            case Manager::FILTER_LENGTH_MAX_100:
                $result = self::TOKEN_LENGTH_MAX_100;
                break;

            case Manager::FILTER_LENGTH_MIN_100:
                $result = self::TOKEN_LENGTH_MIN_100;
                break;

            case Manager::FILTER_LENGTH_MIN_200:
                $result = self::TOKEN_LENGTH_MIN_200;
                break;

            case Manager::FILTER_LENGTH_MIN_400:
                $result = self::TOKEN_LENGTH_MIN_400;
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function facility(Filter $filter)
    {
        switch ($filter->getValue()) {

            case Manager::FILTER_FACILITY_CATERING:
                $result = self::TOKEN_FACILITY_CATERING;
                break;

            case Manager::FILTER_FACILITY_INTERNET_WIFI:
                $result = self::TOKEN_FACILITY_INTERNET_WIFI;
                break;

            case Manager::FILTER_FACILITY_SWIMMING_POOL:
                $result = self::TOKEN_FACILITY_SWIMMING_POOL;
                break;

            case Manager::FILTER_FACILITY_SAUNA:
                $result = self::TOKEN_FACILITY_SAUNA;
                break;

            case Manager::FILTER_FACILITY_PRIVATE_SAUNA:
                $result = self::TOKEN_FACILITY_PRIVATE_SAUNA;
                break;

            case Manager::FILTER_FACILITY_PETS_ALLOWED:
                $result = self::TOKEN_FACILITY_PETS_ALLOWED;
                break;

            case Manager::FILTER_FACILITY_FIREPLACE:
                $result = self::TOKEN_FACILITY_FIREPLACE;
                break;

            default:
                $result = null;
        }

        return $result;
    }

    /**
     * @param Filter $filter
     *
     * @return string
     */
    public function theme(Filter $filter)
    {
        switch ($filter->getValue()) {

            case Manager::FILTER_THEME_KIDS:
                $result = self::TOKEN_THEME_KIDS;
                break;

            case Manager::FILTER_THEME_CHARMING_PLACES:
                $result = self::TOKEN_THEME_CHARMING_PLACES;
                break;

            case Manager::FILTER_THEME_10_FOR_APRES_SKI:
                $result = self::TOKEN_THEME_10_FOR_APRES_SKI;
                break;

            case Manager::FILTER_THEME_SUPER_SKI_STATIONS:
                $result = self::TOKEN_THEME_SUPER_SKI_STATIONS;
                break;

            case Manager::FILTER_THEME_WINTER_WELLNESS:
                $result = self::TOKEN_THEME_WINTER_WELLNESS;
                break;

            default:
                $result = null;
        }

        return $result;
    }
}