<?php
namespace AppBundle\Service\Paginator;
use       AppBundle\Service\Paginator\OutOfBoundsException;
use       AppBundle\Entity\Accommodation\Accommodation;
use       Doctrine\ORM\QueryBuilder;
use       Doctrine\ORM\Query;

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
    private $sorter;
    private $accommodation;
    
    /**
     * Constructor
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->setBuilder($builder);
        
        $this->results = $this->hydrate();
        $this->limit   = 10;
        $this->offset  = 0;
    }
    
    /**
     * @param QueryBuilder $builder
     */
    public function setBuilder(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
    
    /**
     * @param callable $sorter
     */
    public function sort(callable $sorter)
    {
        uasort($this->results, $sorter);
    }
    
    /**
     * @param integer $page
     * @return integer
     */
    public function checkCurrentPage($page)
    {
        if (($page > $this->getTotalPages() || $page < 0) && count($paginator) > 0) {
            throw new OutOfBoundsException(sprintf('Page cannot be set to either below zero or above the total pages. You chose: %d, max: (%d)', $page, $this->getTotalPages() + 1));
        }
        
        return (int)$page;
    }
    
    /**
     * @param integer $page
     */
    public function setCurrentPage($page)
    {
        $this->current_page = $this->checkCurrentPage($page);
    }
    
    /**
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }
    
    /**
     * @param integer $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    
    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }
    
    /**
     * @return integer
     */
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
            $results = $this->results();
            
            foreach ($results as $accommodation) {
                $this->total += count($accommodation->getTypes());
            }
        }
        
        return $this->total;
    }
    
    /**
     * @return integer
     */
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
    
    /**
     * @return integer
     */
    public function offset()
    {
        return $this->offset;
    }
    
    /**
     * @return array
     */
    public function results()
    {
        return $this->results;
    }
    
    /**
     * @return array
     */
    public function hydrate()
    {
        $results = $this->builder->getQuery()->getResult(Query::HYDRATE_ARRAY);
        return Accommodation::hydrateRows($results);
    }
}