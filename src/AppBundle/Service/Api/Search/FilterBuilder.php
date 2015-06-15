<?php
namespace AppBundle\Service\Api\Search;

class FilterBuilder
{
    /**
     * @var array
     */
    private $raw;
    
    /**
     * @var array
     */
    private $filters;
    
    /**
     * @const int
     */
    const FILTER_DISTANCE = 1;
    
    /**
     * @const int
     */
    const FILTER_LENGTH   = 2;
    
    /**
     * @const int
     */
    const FILTER_FACILITY = 3;
    
    /**
     * @const int
     */
    const FILTER_BATHROOM = 4;
    
    /**
     * @const int
     */
    const FILTER_THEME    = 5;

    const VALUE_DISTANCE_BY_SLOPE         = 50;
    
    const FILTER_DISTANCE_BY_SLOPE        = 1;
    const FILTER_DISTANCE_MAX_250         = 2;
    const FILTER_DISTANCE_MAX_500         = 3;
    const FILTER_DISTANCE_MAX_1000        = 4;
                                          
    const FILTER_LENGTH_MAX_100           = 1;
    const FILTER_LENGTH_MIN_100           = 2;
    const FILTER_LENGTH_MIN_200           = 3;
    const FILTER_LENGTH_MIN_400           = 4;
                                          
    const FILTER_FACILITY_CATERING        = 1;
    const FILTER_FACILITY_INTERNET_WIFI   = 2;
    const FILTER_FACILITY_SWIMMING_POOL   = 3; 
    const FILTER_FACILITY_SAUNA           = 4; 
    const FILTER_FACILITY_PRIVATE_SAUNA   = 5; 
    const FILTER_FACILITY_PETS_ALLOWED    = 6; 
    const FILTER_FACILITY_FIREPLACE       = 7;
                                          
    const FILTER_BATHROOM_MIN_2           = 1;
    const FILTER_BATHROOM_MIN_3           = 2;
    const FILTER_BATHROOM_MIN_4           = 3;
    const FILTER_BATHROOM_MIN_5           = 4;
    const FILTER_BATHROOM_MIN_6           = 5;
    const FILTER_BATHROOM_MIN_8           = 6;
    const FILTER_BATHROOM_MIN_10          = 7;
                                          
    const FILTER_THEME_KIDS               = 1;
    const FILTER_THEME_CHARMING_PLACES    = 2;
    const FILTER_THEME_10_FOR_APRES_SKI   = 3;
    const FILTER_THEME_SUPER_SKI_STATIONS = 4;
    const FILTER_THEME_WINTER_WELLNESS    = 5;

    /**
     * @const array
     */
    private $options = [
        
        self::FILTER_DISTANCE => [
            'multiple' => false,
        ],
        
        self::FILTER_LENGTH   => [
            'multiple' => false,
        ],
        
        self::FILTER_FACILITY => [
            'multiple' => true,
        ],
        
        self::FILTER_BATHROOM => [
            'multiple' => false,
        ],
        
        self::FILTER_THEME    => [
            'multiple' => true,
        ],
    ];
    
    /**
     * Constructor
     *
     * @param array $raw
     */
    public function __construct(Array $raw)
    {
        $this->raw     = $raw;
        $this->filters = [];
    }
    
    /**
     * This method parses the filter array that was inserted
     * and turns it into an object that is readable by the search builder
     * and service.
     *
     * @return array
     */
    public function parse()
    {
        foreach ($this->raw as $filter => $values) {
            
            if (!array_key_exists($filter, $this->options)) {
                
                unset($this->raw[$filter]);
                continue;
            }
            
            if (is_array($values) && false === $this->options[$filter]['multiple']) {
                $this->raw[$filter] = (count($values) > 0 ? $values[0] : null);
            }
            
            $this->add($filter, $this->raw[$filter]);
        }
    }
    
    public function add($filter, $values)
    {
        $this->filters[$filter] = $values;
        
        return $this;
    }
    
    public function remove($filter, $currentValue = null)
    {
        if (isset($this->filters[$filter])) {
        
            if (null === $currentValue) {
                
                unset($this->filters[$filter]);
                
            } else {
            
                foreach ($this->filters[$filter] as $key => $value) {
                    
                    if ($value === $currentValue) {
                        unset($this->filters[$filter][$key]);
                    }
                }
            }
        }
        
        return $this;
    }
    
    public function raw()
    {
        return $this->raw;
    }
    
    public function has($filter)
    {
        return isset($this->filters[$filter]);
    }
    
    public function filter($filter, $default = null)
    {
        switch ($filter) {
            
            case self::FILTER_DISTANCE:
            case self::FILTER_LENGTH:
            case self::FILTER_FACILITY:
            case self::FILTER_BATHROOM:
            case self::FILTER_THEME:
                $value = (isset($this->filters[$filter]) ? $this->filters[$filter] : $default);
            break;
            
            default:
                $value = $default;
        }
        
        return $value;
    }
    
    public function all()
    {
        return $this->filters;
    }
}