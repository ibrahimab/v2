<?php
namespace AppBundle\Service\Api\Search;
use       AppBundle\Service\FilterService;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
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
    const VALUE_DISTANCE_BY_SLOPE = 50;
    
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
            
            if (false === FilterService::exists($filter)) {
                
                unset($this->raw[$filter]);
                continue;
            }
            
            if (is_array($values) && false === FilterService::multiple($filter)) {
                $this->raw[$filter] = (count($values) > 0 ? $values[0] : null);
            }
            
            $this->add($filter, $this->raw[$filter]);
        }
    }
    
    /**
     * @param int $filter
     * @param int|array $values
     * @return FilterBuilder
     */
    public function add($filter, $values)
    {
        if (true === FilterService::multiple($filter) && false === is_array($values)) {
            
            if (!isset($this->filters[$filter])) {
                $this->filters[$filter] = [];
            }
            
            $this->filters[$filter][] = $values;
            
        } else {
            $this->filters[$filter] = $values;
        }
        
        return $this;
    }
    
    /**
     * @param int $filter
     * @param int|null $currentValue
     * @return FilterBuilder
     */
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
    
    /**
     * @return array
     */
    public function raw()
    {
        return $this->raw;
    }
    
    /**
     * @return boolean
     */
    public function has($filter)
    {
        return isset($this->filters[$filter]);
    }
    
    /**
     * @param int $filter
     * @param int|null $default
     * @return int|null
     */
    public function filter($filter, $default = null)
    {
        switch ($filter) {
            
            case FilterService::FILTER_DISTANCE:
            case FilterService::FILTER_LENGTH:
            case FilterService::FILTER_FACILITY:
            case FilterService::FILTER_THEME:
                $value = (isset($this->filters[$filter]) ? $this->filters[$filter] : $default);
            break;
            
            default:
                $value = $default;
        }
        
        return $value;
    }
    
    /**
     * @return array
     */
    public function all()
    {
        return $this->filters;
    }
}