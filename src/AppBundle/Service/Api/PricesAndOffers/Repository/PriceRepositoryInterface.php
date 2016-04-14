<?php
namespace AppBundle\Service\Api\PricesAndOffers\Repository;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
interface PriceRepositoryInterface
{
    /**
     * @param integer    $weekend
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByWeekend($weekend, $typeIds = null);

    /**
     * @param integer    $persons
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByPersons($persons, $typeIds = null);

    /**
     * @param integer    $weekend
     * @param integer    $persons
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByWeekendAndPersons($weekend, $persons, $typeIds = null);
}