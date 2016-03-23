<?php
namespace AppBundle\Service\Api\Search\Builder;

use AppBundle\Service\Api\Search\SearchException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Sort
{
    /**
     * @var string
     */
    const SORT_ASC    = 1;

    /**
     * @var string
     */
    const SORT_DESC   = 2;

    /**
     * @var string
     */
    const SORT_NORMAL = 3;

    /**
     * @var integer
     */
    const FIELD_ACCOMMODATION_NAME = 1;

    /**
     * @var integer
     */
    const FIELD_TYPE_PRICE         = 2;

    /**
     * @var integer
     */
    const FIELD_TYPE_SEARCH_ORDER  = 3;

    /**
     * @var integer
     */
    private $field;

    /**
     * @var string
     */
    private $direction;

    /**
     * @var array
     */
    private $fields = [
        self::FIELD_ACCOMMODATION_NAME, self::FIELD_TYPE_PRICE, self::FIELD_TYPE_SEARCH_ORDER,
    ];

    /**
     * Constructor
     *
     * @param integer $field
     * @param string $direction
     */
    public function __construct($field = self::FIELD_TYPE_SEARCH_ORDER, $direction = self::SORT_NORMAL)
    {
        $this->field     = $field;
        $this->direction = $direction;

        if (!in_array($field, $this->fields)) {
            throw new SearchException('You can only sort results by accommodation name, type price and search order');
        }
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }
}