<?php
namespace AppBundle\Service\Paginator;
use       AppBundle\Service\Paginator\OutOfBoundsException;
use       Doctrine\ORM\QueryBuilder;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class PaginatorService implements \Iterator, \Countable
{
    private $builder;
    private $position;
    private $offset;
    private $results;
    private $total;
    private $results_count;
    private $current_page;
    private $total_pages;
    private $limit;
    
    /**
     * Constructor
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->setBuilder($builder);
        
        $this->results = $this->builder->getQuery()->getResult();
        $this->limit   = 10;
        $this->offset  = 0;
    }
    
    public function setBuilder(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
    
    public function checkCurrentPage($page)
    {
        if (($page > $this->getTotalPages() || $page < 0) && count($paginator) > 0) {
            throw new OutOfBoundsException(sprintf('Page cannot be set to either below zero or above the total pages. You chose: %d, max: (%d)', $page, $this->getTotalPages() + 1));
        }
        
        return (int)$page;
    }
    
    public function setCurrentPage($page)
    {
        $this->current_page = $this->checkCurrentPage($page);
    }
    
    public function getCurrentPage()
    {
        return $this->current_page;
    }
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getTotalPages()
    {
        if (null === $this->total_pages) {
            $this->total_pages = ($this->getLimit() > 0 ? ((int)ceil($this->resultsCount() / $this->getLimit()) - 1) : 0);
        }
        
        return $this->total_pages;
    }
    
    /**
     * Count results
     *
     * @return integer
     */
    public function count()
    {
        if (null === $this->total) {
            
            $this->total = 0;
            
            foreach ($this->results as $accommodation) {
                $this->total += count($accommodation->getTypes());
            }
        }
        
        return $this->total;
    }
    
    public function resultsCount()
    {
        if (null === $this->results_count) {
            $this->results_count = count($this->results);
        }
        
        return $this->results_count;
    }
    
    /**
     * @return integer
     */
    public function current()
    {
        return ($this->position >= $this->offset && $this->position < ($this->offset() + $this->getLimit()) ? $this->results[$this->position] : false);
    }
    
    /**
     * @return void
     */
    public function next()
    {
        $this->position += 1;
    }
    
    /**
     * @return void
     */
    public function rewind()
    {
        $this->checkCurrentPage($this->getCurrentPage());
        
        $this->position = ($this->getCurrentPage() * $this->getLimit());
        $this->offset   = $this->position;
    }
    
    /**
     * @return integer
     */
    public function key()
    {
        return $this->position;
    }
    
    /**
     * @return boolean
     */
    public function valid()
    {
        $valid = isset($this->results[$this->position]) && $this->position >= $this->offset && $this->position < ($this->getLimit() + $this->offset());
        return $valid;
    }
    
    public function offset()
    {
        return $this->offset;
    }
    
    public function results()
    {
        return $this->results;
    }
}