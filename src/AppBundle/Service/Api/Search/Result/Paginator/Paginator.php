<?php
namespace AppBundle\Service\Api\Search\Result\Paginator;
use       AppBundle\Service\Api\Search\Result\Paginator\OutOfBoundsException;
use       AppBundle\Service\Api\Search\Result\Resultset;
use       AppBundle\Entity\Accommodation\Accommodation;
use       Doctrine\ORM\QueryBuilder;
use       Doctrine\ORM\Query;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Paginator implements \Iterator, \Countable
{
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
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(Resultset $resultset, $limit = 10, $offset = 0)
    {
        $this->resultset = $resultset;
        $this->position  = 0;
        $this->limit     = $limit;
        $this->offset    = $offset;
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
            $this->total_pages = ($this->getLimit() > 0 ? ((int)ceil($this->total() / $this->getLimit()) - 1) : 0);
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
        return $this->resultset->count();
    }

    /**
     * @return integer
     */
    public function total()
    {
        return $this->resultset->total();
    }

    /**
     * @return integer
     */
    public function current()
    {
        return ($this->position >= $this->offset && $this->position < ($this->offset() + $this->getLimit()) ? $this->resultset->results[$this->position] : false);
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
        return isset($this->resultset->results[$this->position]) && $this->position >= $this->offset && $this->position < ($this->getLimit() + $this->offset());
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
        return $this->resultset->results;
    }
}