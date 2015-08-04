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
        
        return $this->additionalCostsCache;
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
            
            $this->types[]  = $result['id'];
            
            if (true === $result['offer']) {
                $this->offers[$result['id']] = $result['offer'];
            }
            
            $this->prices[$result['id']] = $result['price'];
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
            
            $this->types[] = $result['id'];
            
            if (true === $result['offer']) {
                $this->offers[$result['id']] = $result['offer'];
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
        }
        
        if (null !== $this->weekend && null === $this->persons) {
            
            /**
             * Weekend is selected, so we need to first fetch available types
             * so we can restrict the results but also fetch prices and offers
             */
            $this->getDataByWeekend();
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
        if (isset($this->additionCache[$type])) {
            return $this->additionCache[$type];
        }
        
        $cache    = $this->getAdditionalCostsCache();
        $persons  = null === $this->persons ? $maxResidents : $this->persons;
        $addition = 0;
        $seasonId = $this->getAdditionalCostsSeasonId();
        $addition = (isset($cache[$type]) && isset($cache[$type][$seasonId]) ? $cache[$type][$seasonId][$persons] : 0);
        
        if ($show === 1) {
            
            // arrangement
            $addition = ($addition / $persons);
            
        } else {
            
            // accommodation
            $costs = $this->getAdditionalCostsPersonsCache();
            
            if (null !== $this->persons && null !== $this->weekend && isset($costs[$type]) && isset($costs[$type][$this->weekend])) {
                $addition += $costs[$type][$this->weekend];
            }
        }
        
        if ($type == 11417) {
            dump($maxResidents);
        }
        
        return $this->additionCache[$type] = $addition;
    }
}