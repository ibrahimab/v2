<?php
namespace AppBundle\Service\Api\Search\Filter;

use AppBundle\Service\Api\Search\Filter\Filter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Builder
{
    /**
     * @var integer
     */
    const VALUE_DISTANCE_BY_SLOPE = 50;

    /**
     * @var array
     */
    private $raw;

    /**
     * @var array
     */
    private $filters;

    /**
     * Constructor
     *
     * @param array $raw
     */
    public function __construct(array $raw)
    {
        $this->raw     = $raw;
        $this->filters = [];

        // parsing raw filters into objects
        $this->parse();
    }

    /**
     * This method parses the filter array that was inserted
     * and turns it into an object that is readable by the search builder
     * and service.
     *
     * @return Builder
     */
    public function parse()
    {
        foreach ($this->raw as $filter => $values) {

            if (false === Manager::exists($filter)) {

                unset($this->raw[$filter]);
                continue;
            }

            if (is_array($values) && false === Manager::multiple($filter)) {
                $this->raw[$filter] = (count($values) > 0 ? $values[0] : null);
            }

            if (!is_array($values) && true === Manager::multiple($filter)) {
                $this->raw[$filter] = [$values];
            }

            $this->filters[$filter] = new Filter($filter, (is_array($this->raw[$filter]) ? array_map('intval', $this->raw[$filter]) : intval($this->raw[$filter])));
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
     * @return array
     */
    public function all()
    {
        return $this->filters;
    }

    /**
     * @param integer      $filter
     * @param integer|null $default
     *
     * @return integer|null
     */
    public function filter($filter, $default = null)
    {
        switch ($filter) {

            case Manager::FILTER_DISTANCE:
            case Manager::FILTER_LENGTH:
            case Manager::FILTER_FACILITY:
            case Manager::FILTER_THEME:
                $value = (isset($this->filters[$filter]) ? $this->filters[$filter] : $default);
            break;

            default:
                $value = $default;
        }

        return $value;
    }
}