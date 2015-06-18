<?php
namespace AppBundle\Service;

class FilterService
{
    /**
     * @const int
     */
    const FILTER_DISTANCE                 = 1;
                                          
    /** @const int */                     
    const FILTER_DISTANCE_BY_SLOPE        = 1;
                                          
    /** @const int */                     
    const FILTER_DISTANCE_MAX_250         = 2;
                                          
    /** @const int */                     
    const FILTER_DISTANCE_MAX_500         = 3;
                                          
    /** @const int */                     
    const FILTER_DISTANCE_MAX_1000        = 4;
                                          
                                          
    /** @const int */                     
    const FILTER_LENGTH                   = 2;
                                          
    /** @const int */                     
    const FILTER_LENGTH_MAX_100           = 1;
                                          
    /** @const int */                     
    const FILTER_LENGTH_MIN_100           = 2;
                                          
    /** @const int */                     
    const FILTER_LENGTH_MIN_200           = 3;
                                          
    /** @const int */                     
    const FILTER_LENGTH_MIN_400           = 4;
    
    
    /** @const int */
    const FILTER_FACILITY                 = 3;
    
    /** @const int */
    const FILTER_FACILITY_CATERING        = 1;
    
    /** @const int */
    const FILTER_FACILITY_INTERNET_WIFI   = 2;
    
    /** @const int */
    const FILTER_FACILITY_SWIMMING_POOL   = 3;
    
    /** @const int */
    const FILTER_FACILITY_SAUNA           = 4;
    
    /** @const int */
    const FILTER_FACILITY_PRIVATE_SAUNA   = 5;
    
    /** @const int */
    const FILTER_FACILITY_PETS_ALLOWED    = 6;
    
    /** @const int */
    const FILTER_FACILITY_FIREPLACE       = 7;
    
    
    /** @const int */
    const FILTER_BATHROOM                 = 4;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_2           = 1;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_3           = 2;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_4           = 3;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_5           = 4;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_6           = 5;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_8           = 6;
    
    /** @const int */
    const FILTER_BATHROOM_MIN_10          = 7;
    
    
    /** @const int */
    const FILTER_THEME                    = 5;
    
    /** @const int */
    const FILTER_THEME_KIDS               = 1;
    
    /** @const int */
    const FILTER_THEME_CHARMING_PLACES    = 2;
    
    /** @const int */
    const FILTER_THEME_10_FOR_APRES_SKI   = 3;
    
    /** @const int */
    const FILTER_THEME_SUPER_SKI_STATIONS = 4;
    
    /** @const int */
    const FILTER_THEME_WINTER_WELLNESS    = 5;
    
    
    /**
     * Constructor
     *
     * @param array $filters
     */
    public function __construct($translator)
    {
        dump(get_class($translator));
    }
    
    /**
     * @param int $filter
     * @param int $value
     * @return string
     */
    public function tokenize($filter, $value)
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
            
            case FilterService::FILTER_BATHROOM:
                $result = $this->bathroom($value);
                break;
            
            case FilterService::FILTER_THEME:
                $result = $this->theme($value);
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
    
    public function translate()
    {
        # code...
    }
    
    /**
     * @param int $value
     * @return string
     */
    protected function distance($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_DISTANCE_BY_SLOPE:
                $result = 'filter-distance-by-slope';
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_250:
                $result = 'filter-distance-max-250';
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_500:
                $result = 'filter-distance-max-500';
                break;
            
            case FilterService::FILTER_DISTANCE_MAX_1000:
                $result = 'filter-distance-max-1000';
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
                $result = 'filter-length-max-100';
                break;
            
            case FilterService::FILTER_LENGTH_MIN_100:
                $result = 'filter-length-min-100';
                break;
            
            case FilterService::FILTER_LENGTH_MIN_200:
                $result = 'filter-length-min-200';
                break;
            
            case FilterService::FILTER_LENGTH_MIN_400:
                $result = 'filter-length-min-400';
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
                $result = 'filter-facility-catering';
                break;
            
            case FilterService::FILTER_FACILITY_INTERNET_WIFI:
                $result = 'filter-facility-internet-wifi';
                break;
            
            case FilterService::FILTER_FACILITY_SWIMMING_POOL:
                $result = 'filter-facility-swimming-pool';
                break;
            
            case FilterService::FILTER_FACILITY_SAUNA:
                $result = 'filter-facility-sauna';
                break;
                
            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:
                $result = 'filter-facility-private-sauna';
                break;
                
            case FilterService::FILTER_FACILITY_PETS_ALLOWED:
                $result = 'filter-facility-pets-allowed';
                break;
                
            case FilterService::FILTER_FACILITY_FIREPLACE:
                $result = 'filter-facility-fireplace';
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
    protected function bathroom($value)
    {
        switch ($value) {
            
            case FilterService::FILTER_BATHROOM_MIN_2:
                $result = 'filter-bathroom-min-2';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_3:
                $result = 'filter-bathroom-min-3';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_4:
                $result = 'filter-bathroom-min-4';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_5:
                $result = 'filter-bathroom-min-5';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_6:
                $result = 'filter-bathroom-min-6';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_8:
                $result = 'filter-bathroom-min-';
                break;
            
            case FilterService::FILTER_BATHROOM_MIN_10:
                $result = 'filter-bathroom-min-10';
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
                $result = 'filter-theme-kids';
                break;
                
            case FilterService::FILTER_THEME_CHARMING_PLACES:
                $result = 'filter-theme-charming-places';
                break;
                
            case FilterService::FILTER_THEME_10_FOR_APRES_SKI:
                $result = 'filter-theme-10-for-apres-ski';
                break;
                
            case FilterService::FILTER_THEME_SUPER_SKI_STATIONS:
                $result = 'filter-theme-super-ski-station';
                break;
                
            case FilterService::FILTER_THEME_winter-wellness:
                $result = 'filter-theme-winter-wellness';
                break;
                
            default:
                $result = null;
        }
        
        return $result;
    }
}