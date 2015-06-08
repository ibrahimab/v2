<?php
namespace AppBundle\Service\Api\Search;

class FilterBuilder
{
    /**
     * @var array
     */
    private $raw;
    
    const FILTER_CATEGORY_DISTANCE = 1;
    const FILTER_CATEGORY_LENGTH   = 2;
    const FILTER_CATEGORY_FACILITY = 3;
    const FILTER_CATEGORY_BATHROOM = 4;
    const FILTER_CATEGORY_THEME    = 5;
    
    private $categories = [
        
        self::FILTER_CATEGORY_DISTANCE => [
            'multiple' => false,
        ],
        
        self::FILTER_CATEGORY_LENGTH   => [
            'multiple' => false,
        ],
        
        self::FILTER_CATEGORY_FACILITY => [
            'multiple' => false,
        ],
        
        self::FILTER_CATEGORY_BATHROOM => [
            'multiple' => false,
        ],
        
        self::FILTER_CATEGORY_THEME    => [
            'multiple' => false,
        ],
    ];
    
    /**
     * Constructor
     *
     * @param array $raw
     */
    public function __construct(Array $raw)
    {
        $this->raw    = $raw;
        $this->blocks = [];
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
        foreach ($this->raw as $category => $values) {
            
            if (!array_key_exists($category, $this->categories)) {
                
                unset($this->raw[$category]);
                continue;
            }
            
            if (is_array($values) && false === $this->categories[$category]['multiple']) {
                $this->raw[$category] = (count($values) > 0 ? $values[0] : null);
            }
            
            $this->add($category, $values);
        }
    }
    
    public function add($category, $values)
    {
        $this->blocks[$category] = $values;
        
        return $this;
    }
    
    public function remove($category, $currentValue = null)
    {
        if (isset($this->blocks[$category])) {
        
            if (null === $currentValue) {
                
                unset($this->blocks[$category]);
                
            } else {
            
                foreach ($this->blocks[$category] as $key => $value) {
                    
                    if ($value === $currentValue) {
                        unset($this->blocks[$category][$key]);
                    }
                }
            }
        }
        
        return $this;
    }
    
    public function data()
    {
        return $this->data;
    }
}