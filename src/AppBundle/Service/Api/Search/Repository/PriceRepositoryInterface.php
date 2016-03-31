<?php
namespace AppBundle\Service\Api\Search\Repository;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
interface PriceRepositoryInterface
{
    /**
     * @param integer $weekend
     *
     * @return array
     */
    public function getPricesByWeekend($weekend);

    /**
     * @param integer $persons
     *
     * @return array
     */
    public function getPricesByPersons($persons);
}