<?php
namespace AppBundle\Service\Api\Search\Result\Paginator;

use       AppBundle\Service\Api\Search\Result\Paginator\OutOfBoundsException;
use       AppBundle\Service\Api\Search\Result\Resultset;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Paginator implements \Iterator, \Countable
{
    /**
     * @var array
     */
    private $results;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var integer
     */
    private $offset;

    /**
     * @var integer
     */
    private $total;

    /**
     * @var integer
     */
    private $count;

    /**
     * @var integer
     */
    private $current_page;

    /**
     * @var integer
     */
    private $total_pages;

    /**
     * @var integer
     */
    private $limit;

    /**
     * Constructor
     *
     * @param array   $results
     * @param integer $limit
     */
    public function __construct($results, $page, $limit = 10)
    {
        $this->results      = array_values($results);
        $this->position     = 0;
        $this->limit        = $limit;
        $this->offset       = 0;
        $this->current_page = $this->checkCurrentPage($page);
    }

    /**
     * @param integer $page
     * @return integer
     */
    public function checkCurrentPage($page)
    {
        if (($page > $this->getTotalPages() || $page < 0)) {
            throw new OutOfBoundsException(sprintf('Page cannot be set to either below zero or above the total pages. You chose: %d, max: (%d)', $page, $this->getTotalPages() + 1));
        }

        return (int)$page;
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

            $this->total_pages = ($this->getLimit() > 0 ? ((int)ceil($this->count() / $this->getLimit()) - 1) : 0);
            $this->total_pages = ($this->total_pages < 0 ? 0 : $this->total_pages);
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
        if (null === $this->count) {
            $this->count = count($this->results);
        }

        return $this->count;
    }

    /**
     * @return integer
     */
    public function total()
    {
        if (null === $this->total) {
            $this->total = array_sum(array_map('count', $this->results));
        }

        return $this->total;
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
        return isset($this->results[$this->position]) && $this->position >= $this->offset && $this->position < ($this->getLimit() + $this->offset());
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
}