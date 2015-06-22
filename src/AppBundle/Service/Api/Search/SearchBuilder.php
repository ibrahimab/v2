<?php
namespace AppBundle\Service\Api\Search;

/**
 * SearchBuilder
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class SearchBuilder
{   
    /** @const int */
    const BLOCK_WHERE                    = 1,
          BLOCK_LIMIT                    = 2,
          BLOCK_OFFSET                   = 3,
          BLOCK_SORT_BY                  = 4,
          BLOCK_SORT_ORDER               = 5,
          BLOCK_FILTER                   = 6;
          
    /** @const int */
    const WHERE_WEEKEND_SKI              = 1,
          WHERE_ACCOMMODATION            = 2,
          WHERE_COUNTRY                  = 3,
          WHERE_REGION                   = 4,
          WHERE_PLACE                    = 5,
          WHERE_BEDROOMS                 = 6,
          WHERE_BATHROOMS                = 7;
    
    /** @const int */
    const SORT_BY_ACCOMMODATION_NAME     = 1,
          SORT_BY_TYPE_PRICE             = 2,
          SORT_BY_TYPE_SEARCH_ORDER      = 3;
    
    /** @const string */
    const SORT_ORDER_ASC                 = 'asc',
          SORT_ORDER_DESC                = 'desc';
    
    /** 
     * @var array 
     */
    private static $DEFAULT_BLOCK_WHERE  = [];
    
    /** 
     * @var int 
     */
    private static $DEFAULT_BLOCK_LIMIT  = 10;
    
    /** 
     * @var int 
     */
    private static $DEFAULT_BLOCK_OFFSET = 0;
    
    /** 
     * @var array 
     */
    private $blocks;
    
    /**
     * Constructor
     *
     * @param SearchService $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
        $this->blocks        = [
            
            self::BLOCK_WHERE  => self::$DEFAULT_BLOCK_WHERE,
            self::BLOCK_LIMIT  => self::$DEFAULT_BLOCK_LIMIT,
            self::BLOCK_OFFSET => self::$DEFAULT_BLOCK_OFFSET,
        ];
    }
    
    /**
     * Setting limit
     *
     * @param int $limit
     * @return SearchBuilder
     */
    public function limit($limit)
    {
        $this->blocks[self::BLOCK_LIMIT] = intval($limit);
        
        return $this;
    }
    
    /**
     * Setting offset
     *
     * @param int $offset
     * @return SearchBuilder
     */
    public function offset($offset)
    {
        $this->blocks[self::BLOCK_OFFSET] = intval($offset);
        
        return $this;
    }
    
    /**
     * Adding where
     *
     * @param string $field
     * @param mixed $value
     * @return SearchBuilder
     */
    public function where($field, $value)
    {
        $this->blocks[self::BLOCK_WHERE][] = [
            
            'field' => $field,
            'value' => $value,
        ];
        
        return $this;
    }
    
    /**
     * @param int $field
     * @param int $order
     * @return SearchBuilder
     */
    public function sort($field, $order)
    {
        $this->blocks[self::BLOCK_SORT_BY]    = $field;
        $this->blocks[self::BLOCK_SORT_ORDER] = $order;
        
        return $this;
    }
    
    /**
     * @param int $block
     * @return mixed
     */
    public function block($block, $default = null)
    {
        switch ($block) {
            
            case self::BLOCK_WHERE:
            case self::BLOCK_LIMIT:
            case self::BLOCK_OFFSET:
            case self::BLOCK_SORT_BY:
            case self::BLOCK_SORT_ORDER:
            case self::BLOCK_FILTER:
                $value = (isset($this->blocks[$block]) ? $this->blocks[$block] : $default);
            break;
            
            default:
                $value = $default;
        }
        
        return $value;
    }
    
    /**
     * @param array $data
     * @return SearchBuilder
     */
    public function filter($data)
    {
        if (null !== $data) {
            
            $this->blocks[self::BLOCK_FILTER] = new FilterBuilder($data);
            $this->blocks[self::BLOCK_FILTER]->parse();
        }
        
        return $this;
    }
    
    /**
     * Returning the building blocks of this query
     *
     * @return SearchBuilder
     */
    public function blocks()
    {
        return $this->blocks;
    }
    
    /**
     * Returning results
     *
     * @return array
     */
    public function results()
    {
        return $this->searchService->results();
    }
}