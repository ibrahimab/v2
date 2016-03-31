<?php
namespace AppBundle\Service\Api\Search\Result;
use       AppBundle\Service\Api\Search\SearchBuilder;

/**
 * get the text type to show around the price on the search results page
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 **/
class PriceTextType
{

    /**
     * @var integer
     */
    const PRICE_TEXT_ARRANGEMENT_FROM = 1;

    /**
     * @var integer
     */
    const PRICE_TEXT_ARRANGEMENT = 2;

    /**
     * @var integer
     */
    const PRICE_TEXT_ACCOMMODATION_FROM = 3;

    /**
     * @var integer
     */
    const PRICE_TEXT_ACCOMMODATION = 4;

    /**
     * accommodation resultset
     *
     * @var array
     **/
    private $accommodation;

    /**
     * date search condition
     *
     * @var integer
     **/
    private $whereDate;

    /**
     * number of persons search condition
     *
     * @var integer
     **/
    private $wherePersons;

    /**
     * is this a resale price?
     *
     * @var boolean
     **/
    private $isResale;

    /**
     * @param array $accommodation
     * @param integer $whereDate
     * @param integer $wherePersons
     */
    function __construct($accommodation, $whereDate, $wherePersons, $isResale)
    {
        $this->accommodation = $accommodation;
        $this->whereDate     = $whereDate;
        $this->wherePersons  = $wherePersons;
        $this->isResale      = $isResale;
    }

    /**
     * get the type of text to show around the price
     *
     * @param array $accommodation
     * @return integer
     **/
    public function get()
    {

        if ($this->accommodation['show'] == 3 || $this->isResale) {
            // accommodation
            if (count($this->accommodation['types']) >= 2 || !$this->whereDate) {
                return self::PRICE_TEXT_ACCOMMODATION_FROM;
            } else {
                return self::PRICE_TEXT_ACCOMMODATION;
            }
        } else {
            // arrangement
            if (count($this->accommodation['types']) >= 2 || !$this->whereDate || !$this->wherePersons) {
                return self::PRICE_TEXT_ARRANGEMENT_FROM;
            } else {
                return self::PRICE_TEXT_ARRANGEMENT;
            }
        }
    }
}
