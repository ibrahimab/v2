<?php
namespace AppBundle\Service\Api\Search\Builder;

use AppBundle\Service\Api\Search\SearchException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 */
class Where
{
    /** @var integer */
    const WHERE_WEEKEND_SKI   = 1;

    /** @var integer */
    const WHERE_ACCOMMODATION = 2;

    /** @var integer */
    const WHERE_COUNTRY       = 3;

    /** @var integer */
    const WHERE_REGION        = 4;

    /** @var integer */
    const WHERE_PLACE         = 5;

    /** @var integer */
    const WHERE_BEDROOMS      = 6;

    /** @var integer */
    const WHERE_BATHROOMS     = 7;

    /** @var integer */
    const WHERE_PERSONS       = 8;

    /** @var integer */
    const WHERE_FREESEARCH    = 9;

    /** @var integer */
    const WHERE_WEEKEND       = 10;

    /** @var integer */
    const WHERE_SUPPLIER      = 11;

    /** @var integer */
    const WHERE_TYPE          = 12;

    /**
     * @var integer
     */
    private $clause;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $allowedClauses = [

        self::WHERE_WEEKEND_SKI,
        self::WHERE_ACCOMMODATION,
        self::WHERE_COUNTRY,
        self::WHERE_REGION,
        self::WHERE_PLACE,
        self::WHERE_BEDROOMS,
        self::WHERE_BATHROOMS,
        self::WHERE_PERSONS,
        self::WHERE_FREESEARCH,
        self::WHERE_WEEKEND,
        self::WHERE_SUPPLIER,
        self::WHERE_TYPE,
    ];

    /**
     * Constructor
     *
     * @param integer $clause
     * @param mixed   $value
     */
    public function __construct($clause, $value)
    {
        $this->clause = $clause;
        $this->value  = $value;

        if (!in_array($clause, $this->allowedClauses)) {
            throw new SearchException('You are only allowed to scope results by: weekendski, accommodation, type, country, region, place, bedrooms, bathrooms, types, persons, suppliers and freesearch');
        }
    }

    /**
     * @return integer
     */
    public function getClause()
    {
        return $this->clause;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
