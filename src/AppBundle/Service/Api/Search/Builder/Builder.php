<?php
namespace AppBundle\Service\Api\Search\Builder;

use AppBundle\Service\Api\Search\Filter\Builder as FilterBuilder;
use AppBundle\Service\Api\Search\SearchException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Builder
{
    /**
     * @var array
     */
    private $clauses;

    /**
     * Constructor
     *
     * @param integer $page
     * @param integer $limit
     * @param Sort    $sort
     */
    public function __construct()
    {
        $this->clauses = [];
    }

    /**
     * @param FilterBuilder $filterBuilder
     *
     * @return Builder
     */
    public function setFilterBuilder(FilterBuilder $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @return FilterBuilder
     */
    public function getFilterBuilder()
    {
        return $this->filterBuilder;
    }

    /**
     * @param Where $where
     *
     * @return Builder
     */
    public function addClause(Where $where)
    {
        $this->clauses[$where->getClause()] = $where;
        return $this;
    }

    /**
     * @param integer $clause
     *
     * @return void
     */
    public function removeClause($clause)
    {
        unset($this->clauses[$clause]);
    }

    /**
     * @return Where[]
     */
    public function getClauses()
    {
        return $this->clauses;
    }

    /**
     * @param integer $clause
     *
     * @return Where|boolean
     */
    public function getClause($clause)
    {
        return (isset($this->clauses[$clause]) ? $this->clauses[$clause] : false);
    }
}