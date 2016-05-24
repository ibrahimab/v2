<?php
namespace AppBundle\Service\Api\Search;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Params
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array|false
     */
    private $countries;

    /**
     * @var array|false
     */
    private $regions;

    /**
     * @var array|false
     */
    private $places;

    /**
     * @var array|false
     */
    private $accommodations;

    /**
     * @var array|false
     */
    private $filters;

    /**
     * @var integer|false
     */
    private $bedrooms;

    /**
     * @var integer|false
     */
    private $bathrooms;

    /**
     * @var integer|false
     */
    private $weekend;

    /**
     * @var integer|false
     */
    private $persons;

    /**
     * @var integer|false
     */
    private $suppliers;

    /**
     * @var string|false
     */
    private $freesearch;

    /**
     * @var integer|false
     */
    private $sort;

    /**
     * @var integer|false
     */
    private $page;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->params = $request->getQueryParams();
    }

    /**
     * @return array|false
     */
    public function getCountries()
    {
        return $this->getArrayItems('countries', 'c');
    }

    /**
     * @return array|false
     */
    public function getRegions()
    {
        $items = $this->getArrayItems('regions', 'r');

        return ($items !== false ? array_map('intval', $items) : $items);
    }

    /**
     * @return array|false
     */
    public function getPlaces()
    {
        $items = $this->getArrayItems('places', 'pl');

        return ($items !== false ? array_map('intval', $items) : $items);
    }

    /**
     * @return array|false
     */
    public function getAccommodations()
    {
        return $this->getArrayItems('accommodations', 'a');
    }

    /**
     * @param array $filters
     *
     * @return void
     */
    public function setFilters($filters)
    {
        $this->filters = [];

        foreach ($filters as $filter) {
            $this->filters[$filter->getFilter()] = $filter->getValue();
        }
    }

    /**
     * @return array|false
     */
    public function getFilters()
    {
        if (null === $this->filters) {

            $raw = $this->getArrayItems('filters', 'f');

            if (false !== $raw) {

                $filters = [];

                foreach ($raw as $filter => $data) {

                    $filter = intval($filter);

                    if (is_array($data)) {
                        $filters[$filter] = array_map('intval', $data);
                    } else {
                        $filters[$filter] = intval($data);
                    }
                }

                $this->filters = $filters;

            } else {
                $this->filters = false;
            }
        }

        return $this->filters;
    }

    /**
     * @return integer|false
     */
    public function getBedrooms()
    {
        return $this->getIntegerItem('bedrooms', 'be');
    }

    /**
     * @return integer|false
     */
    public function getBathrooms()
    {
        return $this->getIntegerItem('bathrooms', 'ba');
    }

    /**
     * @return integer|false
     */
    public function getSuppliers()
    {
        return $this->getArrayItems('suppliers', 'supp');
    }

    /**
     * @return integer|false
     */
    public function getWeekend()
    {
        return $this->getIntegerItem('weekend', 'w');
    }

    /**
     * @return integer|false
     */
    public function getPersons()
    {
        return $this->getIntegerItem('persons', 'pe');
    }

    /**
     * @return integer|false
     */
    public function getPage()
    {
        $page = intval($this->getIntegerItem('page', 'p'));
        return ($page === 0 ? $page : ($page - 1));
    }

    /**
     * @return string|false
     */
    public function getFreesearch()
    {
        return $this->getItem('freesearch', 'fs');
    }

    /**
     * @return string|false
     */
    public function getSort()
    {
        return $this->getIntegerItem('sort', 's');
    }

    /**
     * @param string $name
     * @param string $param
     *
     * @return array|false
     */
    private function getArrayItems($name, $param)
    {
        if (null === $this->{$name}) {
            $this->{$name} = $this->getParam($param, false);
        }

        if (!is_array($this->{$name}) || count($this->{$name}) === 0) {
            $this->{$name} = false;
        }

        return $this->{$name};
    }

    /**
     * @param string $name
     * @param string $param
     *
     * @return integer|false
     */
    private function getIntegerItem($name, $param)
    {
        if (null === $this->{$name}) {
            $this->{$name} = $this->getParam($param, false);
        }

        if (false !== $this->{$name}) {
            $this->{$name} = intval($this->{$name});
        }

        return $this->{$name};
    }

    /**
     * @param string $name
     * @param string $param
     *
     * @return string|false
     */
    private function getItem($name, $param)
    {
        if (null === $this->{$name}) {
            $this->{$name} = $this->getParam($param, false);
        }

        return $this->{$name};
    }

    /**
     * @param integer    $param
     * @param mixed|null $default
     *
     * @return mixed
     */
    private function getParam($param, $default = null)
    {
        if (!isset($this->params[$param])) {
            return $default;
        }

        return $this->params[$param];
    }
}