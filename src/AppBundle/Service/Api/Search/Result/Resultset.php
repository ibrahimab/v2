<?php
namespace AppBundle\Service\Api\Search\Result;

use AppBundle\Service\Api\Search\Builder\Sort;
use AppBundle\Service\Api\Search\Result\Paginator\Paginator;
use AppBundle\Service\Api\Search\Result\PriceTextType;
use AppBundle\Service\Api\Search\Repository\PriceRepositoryInterface;
use AppBundle\Service\Api\Search\Repository\OfferRepositoryInterface;
use AppBundle\Service\Api\Legacy\StartingPrice;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Resultset
{
    /** @var integer */
    const KIND_CHALET           = 1;

    /** @var integer */
    const KIND_APARTMENT        = 2;

    /** @var integer */
    const KIND_HOTEL            = 3;

    /** @var integer */
    const KIND_CHALET_APARTMENT = 4;

    /** @var integer */
    const KIND_HOLIDAY_HOUSE    = 6;

    /** @var integer */
    const KIND_VILLA            = 7;

    /** @var integer */
    const KIND_CASTLE           = 8;

    /** @var integer */
    const KIND_HOLIDAY_PARK     = 9;

    /** @var integer */
    const KIND_AGRITURISMO      = 10;

    /** @var integer */
    const KIND_DOMAIN           = 11;

    /** @var integer */
    const KIND_PENSION          = 12;

    /**
     * @var array
     */
    private $raw;

    /**
     * @var array
     */
    private $config;

    /**
     * Made public to allow assign by-reference to reduce memory usage
     * for big resultsets
     *
     * @var array
     */
    public $results;

    /**
     * @var integer|boolean
     */
    private $weekend;

    /**
     * @var integer|boolean
     */
    private $persons;

    /**
     * @var array
     */
    private $types;

    /**
     * @var array
     */
    private $typeIds;

    /**
     * @var array
     */
    private $prices;

    /**
     * @var array
     */
    private $cheapest;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var StartingPrice
     */
    private $startingPrice;

    /**
     * @var PriceRepository
     */
    private $priceRepository;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepository;

    /**
     * @var array
     */
    public static $kindIdentifiers = [

        self::KIND_CHALET           => 'chalet',
        self::KIND_APARTMENT        => 'apartment',
        self::KIND_HOTEL            => 'hotel',
        self::KIND_CHALET_APARTMENT => 'chalet-apartment',
        self::KIND_HOLIDAY_HOUSE    => 'holiday-house',
        self::KIND_VILLA            => 'villa',
        self::KIND_CASTLE           => 'castle',
        self::KIND_HOLIDAY_PARK     => 'holiday-park',
        self::KIND_AGRITURISMO      => 'agriturismo',
        self::KIND_DOMAIN           => 'domain',
        self::KIND_PENSION          => 'pension',
    ];

    /**
     * @param array   $results
     * @param array   $config
     * @param integer $weekend
     * @param integer $persons
     */
    public function __construct(array $raw, array $config, $weekend = false, $persons = false)
    {
        $this->raw      = $raw;
        $this->results  = [];
        $this->config   = $config;
        $this->weekend  = $weekend;
        $this->persons  = $persons;
        $this->types    = [];
        $this->prices   = [];
        $this->cheapest = [];
    }

    /**
     * @param StartingPrice $startingPrice
     *
     * @return Repository
     */
    public function setStartingPrice(StartingPrice $startingPrice)
    {
        $this->startingPrice = $startingPrice;
    }

    /**
     * @param PriceRepositoryInterface $priceRepository
     */
    public function setPriceRepository(PriceRepositoryInterface $priceRepository)
    {
        $this->priceRepository = $priceRepository;
    }

    /**
     * @param OfferRepositoryInterface $offerRepository
     */
    public function setOfferRepository(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * @return Resultset
     */
    public function prepare()
    {
        $this->results = [];
        $prices        = [];
        $offers        = [];

        if (false === $this->weekend && false === $this->persons) {
            $prices = $this->startingPrice->getStartingPrices($this->getTypeIds());
        }

        if (false !== $this->weekend || false !== $this->persons) {
            $prices = $this->fetchPrices();
        }

        if (false === $this->weekend && false === $this->persons) {

            // no weekend and no persons
            // select offers from repository
            $offers = $this->fetchOffers();
        }

        foreach ($this->raw as $row) {

            $row['price'] = (isset($row['price']) ? floatval($row['price']) : 0);

            if (isset($prices[$row['type_id']])) {
                $row['price'] = $prices[$row['type_id']];
            }

            if (!isset($row['show_as_discount']) && isset($offers[$row['type_id']])) {

                // discount fields not available
                // but offer repository does have them
                // taking it over from offer repository
                $row['discount_color']      = $offers[$row['type_id']]['discount_color'];
                $row['show_as_discount']    = $offers[$row['type_id']]['show_as_discount'];
                $row['show_exact_discount'] = $offers[$row['type_id']]['discount_percentage'];
                $row['discount_percentage'] = $offers[$row['type_id']]['discount_percentage'];
                $row['discount_euro']       = $offers[$row['type_id']]['discount_euro'];
            }

            if (isset($row['show_as_discount'])) {

                // discount fields are available
                $row['discount_color']      = (intval($row['discount_color']) === 1);
                $row['show_as_discount']    = (intval($row['show_as_discount']) === 1);
                $row['show_exact_discount'] = (intval($row['show_exact_discount']) === 1);
                $row['discount_percentage'] = floatval($row['discount_percentage']);
                $row['discount_amount']     = floatval($row['discount_amount']);
                $row['discount_type']       = null;

                if (true === $row['show_as_discount'] || true === $row['discount_color']) {

                    // discount is active
                    // checking whether it is percentage or amount
                    $row['offer']         = true;
                    $row['discount_type'] = ($row['discount_amount'] > 0 ? 'amount' : 'percentage');
                    $row['show_discount'] = ($row['discount_percentage'] > 0 || $row['discount_amount'] > 0);
                }
            }

            $row['type_id'] = intval($row['type_id']);
            $row['accommodation_id'] = intval($row['accommodation_id']);
            $row['separate_in_search'] = intval($row['separate_in_search']);
            $row['group_id'] = $this->getGroupId($row['separate_in_search'], $row['accommodation_id'], $row['type_id']);
            $row['kind_identifier'] = static::getKindIdentifier($row['kind']);

            $this->results[$row['group_id']][] = $row;
            $this->types[$row['type_id']] = $row;
            $this->typeIds[] = $row['type_id'];
            $this->prices[$row['group_id']][$row['type_id']] = $row['price'];
        }

        $this->prepareTypeCount();
        $this->prepareCheapestRows();
        $this->preparePriceTypeText();

        return $this;
    }

    /**
     * @return array
     */
    public function getTypeIds()
    {
        if (null === $this->typeIds) {
            $this->typeIds = array_map('intval', array_column($this->raw, 'type_id'));
        }

        return $this->typeIds;
    }

    /**
     * @param Sort $sort
     *
     * @return Resultset
     */
    public function sort(Sort $sort)
    {
        $sorter        = new Sorter($this->config, $sort->getDirection(), $this->persons);
        $this->results = $sorter->sort($this->results);

        return $this;
    }

    /**
     * @param integer $page
     *
     * @return Paginator
     */
    public function paginate($page, $limit)
    {
        if (null === $this->paginator) {
            $this->paginator = new Paginator($this->results, $page, $limit);
        }

        return $this->paginator;
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param integer $separate
     * @param integer $accommodationId
     * @param integer $typeId
     *
     * @return string
     */
    public function getGroupId($separate, $accommodationId, $typeId)
    {
        return ($separate === 1 ? ($accommodationId . '_' . $typeId) : $accommodationId);
    }

    /**
     * @param integer $groupId
     *
     * @return array|null
     */
    public function getCheapestRow($groupId)
    {
        return (isset($this->cheapest[$groupId]) ? $this->types[$this->cheapest[$groupId]] : null);
    }

    /**
     * @return void
     */
    public function prepareCheapestRows()
    {
        foreach ($this->prices as $groupId => $prices) {

            $min = 0;

            if (count($prices) > 0) {
                $min = min($prices);
            }

            foreach ($prices as $typeId => $price) {

                if ($min === $price) {
                    $this->cheapest[$groupId] = $typeId;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function prepareTypeCount()
    {
        foreach ($this->results as $groupId => $rows) {

            $count = count($rows);
            $count = ($count > 1 ? ($count - 1) : $count);

            foreach ($rows as $key => $row) {

                $this->results[$groupId][$key]['total_types'] = $count;
                $this->types[$row['type_id']]['total_types'] = $count;
            }
        }
    }

    /**
     * @return void
     */
    public function preparePriceTypeText()
    {
        foreach ($this->results as $groupId => $rows) {

            $cheapestId = $this->cheapest[$groupId];

            foreach ($rows as $key => $row) {

                if ($row['type_id'] === $cheapestId) {

                    $priceTextType = new PriceTextType($this->results[$groupId][$key], $this->weekend, $this->persons, $this->resale);
                    $priceText     = $priceTextType->get();

                    $this->results[$groupId][$key]['price_text_type'] = $priceText;
                    $this->types[$row['type_id']]['price_text_type']  = $priceText;
                }
            }
        }
    }

    /**
     * @param integer $kind
     *
     * @return string|integer
     */
    public static function getKindIdentifier($kind)
    {
        return (isset(self::$kindIdentifiers[$kind]) ? self::$kindIdentifiers[$kind] : $kind);
    }

    /**
     * @return array
     */
    private function fetchPrices()
    {
        $prices = [];

        if (false !== $this->weekend && false === $this->persons) {

            // only weekend was selected
            $prices = $this->priceRepository->getPricesByWeekend($this->weekend);
        }

        if (false === $this->weekend && false !== $this->persons) {

            // only persons was selected
            $prices = $this->priceRepository->getPricesByPersons($this->persons);
        }

        return $prices;
    }

    /**
     * @return array
     */
    private function fetchOffers()
    {
        return $this->offerRepository->getOffers();
    }
}