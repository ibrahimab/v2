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
    /**
     * @const int
     */
    const BLOCK_WHERE  = 1;
                      
    /**               
     * @const int     
     */               
    const BLOCK_LIMIT  = 2;
                      
    /**               
     * @const int     
     */               
    const BLOCK_OFFSET = 3;
    
    /**
     * @const array
     */
    private static $DEFAULT_BLOCK_WHERE  = [];
    
    /**
     * @const int
     */
    private static $DEFAULT_BLOCK_LIMIT  = 10;
    
    /**
     * @const int
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
     * @param int $block
     * @return mixed
     */
    public function block($block)
    {
        switch ($block) {
            
            case self::BLOCK_WHERE:
            case self::BLOCK_LIMIT:
            case self::BLOCK_OFFSET:
                $value = $this->blocks[$block];
            break;
            
            default:
                $value = null;
        }
        
        return $value;
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