<?php
namespace AppBundle\Service;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class FilterService
{
    /** @const int */
    const FILTER_DISTANCE                 = 1;
    
    /** @const int */
    const FILTER_DISTANCE_BY_SLOPE        = 1, 
          FILTER_DISTANCE_MAX_250         = 2, 
          FILTER_DISTANCE_MAX_500         = 3, 
          FILTER_DISTANCE_MAX_1000        = 4;
    
    /** @const string */
    const TOKEN_DISTANCE                  = 'filter-distance',
          TOKEN_DISTANCE_BY_SLOPE         = 'filter-distance-by-slope',
          TOKEN_DISTANCE_MAX_250          = 'filter-distance-max-250',
          TOKEN_DISTANCE_MAX_500          = 'filter-distance-max-500',
          TOKEN_DISTANCE_MAX_1000         = 'filter-distance-max-1000';
    
    
    /** @const int */                     
    const FILTER_LENGTH                   = 2;
                                          
    /** @const int */                     
    const FILTER_LENGTH_MAX_100           = 1,
          FILTER_LENGTH_MIN_100           = 2,
          FILTER_LENGTH_MIN_200           = 3,
          FILTER_LENGTH_MIN_400           = 4;

    /** @const string */
    const TOKEN_LENGTH                    = 'filter-length',
          TOKEN_LENGTH_MAX_100            = 'filter-length-max-100',
          TOKEN_LENGTH_MIN_100            = 'filter-length-min-100',
          TOKEN_LENGTH_MIN_200            = 'filter-length-min-200',
          TOKEN_LENGTH_MIN_400            = 'filter-length-min-400';
    
    
    /** @const int */
    const FILTER_FACILITY                 = 3;
    
    /** @const int */
    const FILTER_FACILITY_CATERING        = 1,
          FILTER_FACILITY_INTERNET_WIFI   = 2,
          FILTER_FACILITY_SWIMMING_POOL   = 3,
          FILTER_FACILITY_SAUNA           = 4,
          FILTER_FACILITY_PRIVATE_SAUNA   = 5,
          FILTER_FACILITY_PETS_ALLOWED    = 6,
          FILTER_FACILITY_FIREPLACE       = 7;
          
    /** @const string */
    const TOKEN_FACILITY                  = 'filter-facility',
          TOKEN_FACILITY_CATERING         = 'filter-facility-catering',
          TOKEN_FACILITY_INTERNET_WIFI    = 'filter-facility-internet-wifi',
          TOKEN_FACILITY_SWIMMING_POOL    = 'filter-facility-swimming-pool',
          TOKEN_FACILITY_SAUNA            = 'filter-facility-sauna',
          TOKEN_FACILITY_PRIVATE_SAUNA    = 'filter-facility-private-sauna',
          TOKEN_FACILITY_PETS_ALLOWED     = 'filter-facility-pets-allowed',
          TOKEN_FACILITY_FIREPLACE        = 'filter-facility-fireplace';
    
    
    /** @const int */
    const FILTER_THEME                    = 5;
    
    /** @const int */
    const FILTER_THEME_KIDS               = 1,
          FILTER_THEME_CHARMING_PLACES    = 2,
          FILTER_THEME_10_FOR_APRES_SKI   = 3,
          FILTER_THEME_SUPER_SKI_STATIONS = 4,
          FILTER_THEME_WINTER_WELLNESS    = 5;
    
    /** @const string */
    const TOKEN_THEME                     = 'filter-theme',
          TOKEN_THEME_KIDS                = 'filter-theme-kids',
          TOKEN_THEME_CHARMING_PLACES     = 'filter-theme-charming-places',
          TOKEN_THEME_10_FOR_APRES_SKI    = 'filter-theme-10-for-apres-ski',
          TOKEN_THEME_SUPER_SKI_STATIONS  = 'filter-theme-super-ski-stations',
          TOKEN_THEME_WINTER_WELLNESS     = 'filter-theme-winter-wellness';
    
    
    /**
     * @param int $filter
     * @param int $value
     * @return string
     */
    public function tokenize($filter, $value = null)
    {
        return null === $value ? $this->filter($filter) : $this->value($filter, $value);
    }
    
    /**
     * @param int $filter
     * @return string
     */
    protected function filter($filter)
    {
        switch ($filter) {
            
            case FilterService::FILTER_DISTANCE:
                $result = self::TOKEN_DISTANCE;
                break;
                
            case self::FILTER_LENGTH:
                $result = self::TOKEN_LENGTH;
                break;
                
            case self::FILTER_FACILITY:
                $result = self::TOKEN_FACILITY;
                break;
                
            case self::FILTER_THEME:
                $result = self::TOKEN_THEME;
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param int $filter
     * @param int $value
     * @return string
     */
    protected function value($filter, $value)
    {
        switch ($filter) {
            
            case FilterService::FILTER_DISTANCE:
                $result = $this->distance($value);
                break;
            
            case FilterService::FILTER_LENGTH:
                $result = $this->length($value);
                break;
            
            case FilterService::FILTER_FACILITY:
                $result = $this->facility($value);
                break;
            
            case FilterService::FILTER_THEME:
                $result = $this->theme($value);
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param int $value
     * @return string
     */
    protected function distance($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_DISTANCE_BY_SLOPE:
                $result = self::TOKEN_DISTANCE_BY_SLOPE;
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_250:
                $result = self::TOKEN_DISTANCE_MAX_250;
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_500:
                $result = self::TOKEN_DISTANCE_MAX_500;
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_1000:
                $result = self::TOKEN_DISTANCE_MAX_1000;
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param int $value
     * @return string
     */
    public function length($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_LENGTH_MAX_100:
                $result = self::TOKEN_LENGTH_MAX_100;
                break;
            
            case FilterService::FILTER_LENGTH_MIN_100:
                $result = self::TOKEN_LENGTH_MIN_100;
                break;
            
            case FilterService::FILTER_LENGTH_MIN_200:
                $result = self::TOKEN_LENGTH_MIN_200;
                break;
            
            case FilterService::FILTER_LENGTH_MIN_400:
                $result = self::TOKEN_LENGTH_MIN_400;
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param int $value
     * @return string
     */
    protected function facility($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_FACILITY_CATERING:
                $result = self::TOKEN_FACILITY_CATERING;
                break;
            
            case FilterService::FILTER_FACILITY_INTERNET_WIFI:
                $result = self::TOKEN_FACILITY_INTERNET_WIFI;
                break;
            
            case FilterService::FILTER_FACILITY_SWIMMING_POOL:
                $result = self::TOKEN_FACILITY_SWIMMING_POOL;
                break;
            
            case FilterService::FILTER_FACILITY_SAUNA:
                $result = self::TOKEN_FACILITY_SAUNA;
                break;
                
            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:
                $result = self::TOKEN_FACILITY_PRIVATE_SAUNA;
                break;
                
            case FilterService::FILTER_FACILITY_PETS_ALLOWED:
                $result = self::TOKEN_FACILITY_PETS_ALLOWED;
                break;
                
            case FilterService::FILTER_FACILITY_FIREPLACE:
                $result = self::TOKEN_FACILITY_FIREPLACE;
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param int $value
     * @return string
     */
    protected function theme($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_THEME_KIDS:
                $result = self::TOKEN_THEME_KIDS;
                break;
                
            case FilterService::FILTER_THEME_CHARMING_PLACES:
                $result = self::TOKEN_THEME_CHARMING_PLACES;
                break;
                
            case FilterService::FILTER_THEME_10_FOR_APRES_SKI:
                $result = self::TOKEN_THEME_10_FOR_APRES_SKI;
                break;
                
            case FilterService::FILTER_THEME_SUPER_SKI_STATIONS:
                $result = self::TOKEN_THEME_SUPER_SKI_STATIONS;
                break;
                
            case FilterService::FILTER_THEME_WINTER_WELLNESS:
                $result = self::TOKEN_THEME_WINTER_WELLNESS;
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
}