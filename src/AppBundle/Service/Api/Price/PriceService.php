<?php
namespace AppBundle\Service\Api\Price;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Old\PricesWrapper;

/**
 * This is the PriceService, with this service you can select prices
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
class PriceService
{
    /**
     * @var PriceServiceRepositoryInterface
     */
    private $priceServiceRepository;

    /**
     * @var PricesWrapper
     */
    private $oldPricesWrapper;

    /**
     * @var \bijkomendekosten
     */
    private $additionalCosts;

    /**
     * @var array
     */
    private $additionalCostsCache;

    /**
     * @var array
     */
    private $additionalCostsPersonsCache;

    /**
     * @var integer
     */
    private $additionalCostsSeasonId;

    /**
     * @var array
     */
    private $additionCache;

    /**
     * @var SeasonConcern
     */
    private $season;

    /**
     * @var integer
     */
    private $weekend;

    /**
     * @var integer
     */
    private $persons;

    /**
     * @var array
     */
    private $prices;

    /**
     * @var array
     */
    private $types;

    /**
     * @var array
     */
    private $offers;

    /**
     * @var array
     */
    private $accommodations;


    /**
     * Constructor
     *
     * @param PriceServiceRepositoryInterface $faqServiceRepository
     */
    public function __construct(PriceServiceRepositoryInterface $priceServiceRepository)
    {
        $this->priceServiceRepository      = $priceServiceRepository;
        $this->types                       = [];
        $this->weekend                     = null;
        $this->persons                     = null;
        $this->prices                      = [];
        $this->offers                      = [];
        $this->accommodations              = [];
        $this->additionalCostsCache        = null;
        $this->additionalCostsPersonsCache = null;
        $this->additionCache               = [];
        $this->additionalCostsSeasonId     = null;
    }

    public function setOldPricesWrapper($oldPricesWrapper)
    {
        $this->oldPricesWrapper = $oldPricesWrapper;
    }

    /**
     * @param SeasonConcern $season
     */
    public function setSeason(SeasonConcern $season)
    {
        $this->season = $season;
    }

    /**
     * @param \bijkomendekosten $additionalCosts
     */
    public function setAdditionalCosts($additionalCosts)
    {
        $this->additionalCosts = $additionalCosts;
    }

    /**
     * @param integer|null $weekend
     */
    public function setWeekend($weekend = null)
    {
        $this->weekend = $weekend;
    }

    /**
     * @param integer|null $persons
     */
    public function setPersons($persons = null)
    {
        $this->persons = $persons;
    }

    /**
     * @return integer|null
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param array $types
     */
    public function setTypes(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @var array
     * @return array
     */
    public function getAccommodations()
    {
        return $this->accommodations;
    }

    /**
     * @param integer $seasonId
     */
    public function setAdditionalCostsSeasonId($seasonId)
    {
        $this->additionalCostsSeasonId = $seasonId;
    }

    /**
     * @return integer
     */
    public function getAdditionalCostsSeasonId()
    {
        return $this->additionalCostsSeasonId;
    }

    /**
     * @return void
     */
    public function getAdditionalCostsCache()
    {
        if (null === $this->additionalCostsCache) {
            $this->additionalCostsCache = $this->additionalCosts->get_complete_cache($this->season->get());
        }

        return $this->additionalCostsCache;
    }

    /**
     * @return void
     */
    public function getAdditionalCostsPersonsCache()
    {
        if (null === $this->additionalCostsCache) {
            $this->additionalCostsPersonsCache = $this->additionalCosts->get_complete_cache_per_persons($this->season->get(), $this->persons);
        }

        return $this->additionalCostsPersonsCache;
    }

    /**
     * This is a deprecated function
     * @TODO: fix this by refactoring it
     * @deprecated
     */
    public function offers($types)
    {
        return $this->priceServiceRepository->offers($types);
    }

    /**
     * @return void
     */
    public function getDataByWeekend()
    {
        $results = $this->priceServiceRepository->getDataByWeekend($this->weekend);

        foreach ($results as $result) {

            if (true === $result['offer']) {
                $this->offers[$result['id']] = $result['offer'];
            }

            if (isset($result['accommodation']) && true == $result['accommodation']) {
                $this->accommodationKinds[$result['id']] = true;
            }

            $this->prices[$result['id']]             = $result['price'];
            $this->accommodationKinds[$result['id']] = true;

            if ($result['price'] > 0) {
                $this->types[] = $result['id'];
            }
        }
    }

    /**
     * @return void
     */
    public function getDataByPersons()
    {
        $results = $this->priceServiceRepository->getDataByPersons($this->persons);
        $costs   = $this->getAdditionalCostsPersonsCache();

        foreach ($results as $key => $result) {

            $this->types[] = (int)$result['id'];

            if (true === $result['offer']) {
                $this->offers[$result['id']] = $result['offer'];
            }

            if (isset($result['accommodation']) && true == $result['accommodation']) {
                $this->accommodations[$result['id']] = true;
            }

            if (isset($result['price'])) {
                $this->prices[$result['id']] = (float)$result['price'];
            }

            if (isset($result['prices'])) {

                // add with additional costs
                if (isset($costs[$result['id']])) {

                    foreach ($result['prices'] as $weekend => $price) {

                        if (isset($costs[$result['id']][$weekend])) {
                            $results[$key]['prices'][$weekend] += floatval($costs[$result['id']][$weekend]);
                        }
                    }
                }

                if (count($results[$key]['prices']) > 0) {
                    $this->prices[$result['id']] = min($results[$key]['prices']);
                }
            }
        }
    }

    /**
     * @return void
     */
    public function getDataByWeekendAndPersons()
    {
        $results = $this->priceServiceRepository->getDataByWeekendAndPersons($this->weekend, $this->persons);

        foreach ($results as $result) {

            if (isset($result['price'])) {
                $this->prices[$result['id']] = $result['price'];
            }

            if (isset($result['prices'])) {

                // add with additional costs
                if (isset($costs[$result['id']])) {

                    foreach ($result['prices'] as $weekend => $price) {

                        if (isset($costs[$result['id']][$weekend])) {
                            $results[$key]['prices'][$weekend] += floatval($costs[$result['id']][$weekend]);
                        }
                    }
                }

                if (count($results[$key]['prices']) > 0) {
                    $this->prices[$result['id']] = min($results[$key]['prices']);
                }
            }

            if (isset($this->prices[$result['id']]) && $this->prices[$result['id']] > 0) {
                $this->types[] = $result['id'];
            }

            if (true === $result['offer']) {
                $this->offers[$result['id']] = $result['offer'];
            }

            if (isset($result['accommodation']) && true === $result['accommodation']) {
                $this->accommodations[$result['id']] = true;
            }
        }
    }

    /**
     * @return array
     */
    public function getDataWithWeekendAndOrPersons()
    {
        $totalTypes = count($this->types);

        if (null === $this->weekend && null === $this->persons && $totalTypes > 0) {

            /**
             * No weekend and persons are selected, so we need to select prices from the cache
             */
            $this->prices = $this->oldPricesWrapper->get($this->types);
            $this->offers = $this->priceServiceRepository->offers($this->types);
        }

        if (null !== $this->weekend && null === $this->persons) {

            /**
             * Weekend is selected, so we need to first fetch available types
             * so we can restrict the results but also fetch prices and offers
             */
            $this->getDataByWeekend();
        }

        if (null === $this->weekend && null !== $this->persons) {

            /**
             * Persons is selected
             */
            $this->getDataByPersons();
        }

        if (null !== $this->weekend && null !== $this->persons) {

            /**
             * Weekend and persons are selected, selecting it from the database
             */
            $this->getDataByWeekendAndPersons();
        }
    }

    /**
     * @param integer
     * @param integer
     * @param integer
     * @return integer
     */
    public function getAdditionalCostsByType($type, $show, $maxResidents)
    {
        if (null === $this->weekend && null === $this->persons) {

            // cache requested, so no addition
            return 0;
        }

        if (isset($this->additionCache[$type])) {
            return $this->additionCache[$type];
        }

        $cache    = $this->getAdditionalCostsCache();
        $addition = 0;
        $seasonId = $this->getAdditionalCostsSeasonId();

        if ($show === 1) {

            $persons  = null === $this->persons ? $maxResidents : $this->persons;
            $addition = (isset($cache[$type]) && isset($cache[$type][$seasonId]) ? $cache[$type][$seasonId][$persons] : 0);

            // arrangement
            $addition = ($addition / $persons);

        } else {

            // accommodation
            $costs    = $this->getAdditionalCostsPersonsCache();
            $persons  = null === $this->persons ? 1 : $this->persons;
            $addition = (isset($cache[$type]) && isset($cache[$type][$seasonId]) ? $cache[$type][$seasonId][$persons] : 0);

            if (null !== $this->persons && null !== $this->weekend && isset($costs[$type]) && isset($costs[$type][$this->weekend])) {
                $addition += $costs[$type][$this->weekend];
            }
        }

        return $this->additionCache[$type] = ceil($addition);
    }

    /**
     * @param  TypeServiceEntityInterface $type
     * @return array
     */
    public function getAvailableData($type)
    {
        return $this->priceServiceRepository->getAvailableData($type);
    }

    /**
     * @param  integer $typeId
     * @param  integer $show
     * @param  array   $weekends
     *
     * @return array
     */
    public function getBookablePersons($typeId, $show, $weekends)
    {
        return $this->priceServiceRepository->getBookablePersons($typeId, $show, $weekends);
    }

    /**
     * @param integer $weekend
     *
     * @return array
     */
    public function availableTypes($weekend)
    {
        return $this->priceServiceRepository->availableTypes($weekend);
    }
}
