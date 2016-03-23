<?php
namespace AppBundle\Service\Api\Search\Filter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Filter
{
    /**
     * @var integer
     */
    private $filter;

    /**
     * @var integer
     */
    private $value;

    /**
     * Constructor
     *
     * @param integer $filter
     * @param integer $value
     */
    public function __construct($filter, $value)
    {
        $this->filter = $filter;
        $this->value  = $value;
    }

    /**
     * @return integer
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }
}