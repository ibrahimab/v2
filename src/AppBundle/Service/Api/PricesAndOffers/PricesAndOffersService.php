<?php
namespace AppBundle\Service\Api\PricesAndOffers;

use AppBundle\Service\Api\PricesAndOffers\Repository\PriceRepositoryInterface;
use AppBundle\Service\Api\PricesAndOffers\Repository\OfferRepositoryInterface;
use AppBundle\Service\Api\Legacy\StartingPrice;
use AppBundle\Service\Api\Search\Params;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory as PsrFactory;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class PricesAndOffersService
{
    /**
     * @var PriceRepositoryInterface
     */
    private $priceRepository;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepository;

    /**
     * @var StartingPrice
     */
    private $startingPrice;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $offers;

    /**
     * @var array
     */
    private $prices;

    /**
     * Constructor
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(PriceRepositoryInterface $priceRepository, OfferRepositoryInterface $offerRepository, StartingPrice $startingPrice)
    {
        $this->priceRepository = $priceRepository;
        $this->offerRepository = $offerRepository;
        $this->startingPrice   = $startingPrice;
    }

    /**
     * This can only be called once per request!
     *
     * @param Request $request
     *
     * @return Params
     */
    public function createParamsFromRequest(Request $request)
    {
        $factory = new PsrFactory();
        return new Params($factory->createRequest($request));
    }

    /**
     * @return array
     */
    public function getOffers($typeIds)
    {
        if (null === $this->offers) {
            $this->offers = $this->offerRepository->getOffers($typeIds);
        }

        return $this->offers;
    }

    /**
     * @param array  $typeIds
     * @param Params $params
     *
     * @return array
     */
    public function getPrices($typeIds, Params $params)
    {
        if (null === $this->prices) {

            $prices  = [];
            $weekend = $params->getWeekend();
            $persons = $params->getPersons();

            if (false === $weekend && false === $persons) {
                $this->prices = $this->startingPrice->getStartingPrices($typeIds);
            }

            if (false !== $weekend || false !== $persons) {
                $this->prices = $this->fetchPrices($weekend, $persons, $typeIds);
            }
        }

        return $this->prices;
    }

    /**
     * @param integer|boolean $weekend
     * @param integer|boolean $persons
     * @param array|null      $typeIds
     *
     * @return array
     */
    private function fetchPrices($weekend, $persons, $typeIds = null)
    {
        $prices = [];

        if (false !== $weekend && false === $persons) {

            // only weekend was selected
            $prices = $this->priceRepository->getPricesByWeekend($weekend, $typeIds);
        }

        if (false === $weekend && false !== $persons) {

            // only persons was selected
            $prices = $this->priceRepository->getPricesByPersons($persons, $typeIds);
        }

        if (false !== $weekend && false !== $persons) {

            // both weekend and persons selected
            $prices = $this->priceRepository->getPricesByWeekendAndPersons($weekend, $persons, $typeIds);
        }

        return $prices;
    }
}