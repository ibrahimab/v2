<?php
namespace AppBundle\Service\Api\Price;

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
     * Constructor
     *
     * @param PriceServiceRepositoryInterface $faqServiceRepository
     */
    public function __construct(PriceServiceRepositoryInterface $priceServiceRepository)
    {
        $this->priceServiceRepository = $priceServiceRepository;
    }

    /**
     * Get types that have offers enabled
     *
     * @param array
     * @return array
     */
    public function offers($types)
    {
        return $this->priceServiceRepository->offers($types);
    }

    /**
     * Get available types and possible offers
     *
     * @param integer $weekend
     * @return array
     */
    public function availableTypes($weekend)
    {
        return $this->priceServiceRepository->availableTypes($weekend);
    }

    /**
     * Get prices with weekend and persons
     *
     * @param integer $weekend
     * @param integer $persons
     * @return array
     */
    public function pricesWithWeekendAndPersons($weekend, $persons)
    {
        return $this->priceServiceRepository->pricesWithWeekendAndPersons($weekend, $persons);
    }
}